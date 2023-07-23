// Cookie related functions (taken from https://stackoverflow.com/questions/4825683/how-do-i-create-and-read-a-value-from-cookie-with-javascript)
function createCookie(name, value, days) {
    var expires;
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    }
    else {
        expires = "";
    }
    document.cookie = name + "=" + value + expires + "; path=/";
}

function getCookie(c_name) {
    if (document.cookie.length > 0) {
        c_start = document.cookie.indexOf(c_name + "=");
        if (c_start != -1) {
            c_start = c_start + c_name.length + 1;
            c_end = document.cookie.indexOf(";", c_start);
            if (c_end == -1) {
                c_end = document.cookie.length;
            }
            return unescape(document.cookie.substring(c_start, c_end));
        }
    }
    return "";
}

// Start of script (pairs.js)

const start = document.getElementById('start');
const container = document.querySelector('.card-container');
var cards = document.querySelectorAll('.card');


// Gameplay related parameters
var level = 1;
var matches = 2;
var difficulty = 0;
var randomEventProbability = 0;
var timeAllocation = 0;
var moveAllocation = 0;

// Stats
let moves = 0;
var score = 0;
var levelScores = [];
let errorEventMode = false;

// Time-related variables
var finish = 0;
var timer = 0;

// Audio-related settings
var sfx = true;
var music = true;
var playback = 0;

function generateBoard(cardNumber = 6) {
	// let cardTemplate = "<div class='card'></div>";
	for (let i = 0; i < Math.floor(cardNumber / matches); i++) {
		let template = generateCard(i);
		container.innerHTML += template;
	}
	pickRandomEvent(randomEventProbability);
	cards = document.querySelectorAll('.card');
	cards.forEach(card => {
		card.style.flexBasis = calcFlexBasis(cards.length);
	});
}

function calcFlexBasis(cards){
	if (cards > 25){
		return "calc(16.6666% - 14px)";
	}
	if (cards > 16){
		return "calc(20% - 14px)";
	}
	else if (cards > 9){
		return "calc(25% - 14px)";
	}
	else {
		return "calc(33.3333% - 14px)";
	}
}
// Emoji randomisation + card generation

const skin = [new Image(), new Image(), new Image(), new Image(), new Image(), new Image(), new Image()]
const eyes = [new Image(), new Image(), new Image(), new Image(), new Image(), new Image()]
const mouth = [new Image(), new Image(), new Image(), new Image(), new Image(), new Image()]

// Skins
skin[0].src = 'assets/skin/red.png';
skin[1].src = 'assets/skin/orange.png';
skin[2].src = 'assets/skin/yellow.png';
skin[3].src = 'assets/skin/green.png';
skin[4].src = 'assets/skin/blue.png';
skin[5].src = 'assets/skin/purple.png';
skin[6].src = 'assets/skin/pink.png';

// Eyes
eyes[0].src = 'assets/eyes/closed.png';
eyes[1].src = 'assets/eyes/laughing.png';
eyes[2].src = 'assets/eyes/long.png';
eyes[3].src = 'assets/eyes/normal.png';
eyes[4].src = 'assets/eyes/rolling.png';
eyes[5].src = 'assets/eyes/winking.png';

// Mouth
mouth[0].src = 'assets/mouth/surprise.png';
mouth[1].src = 'assets/mouth/open.png';
mouth[2].src = 'assets/mouth/sad.png';
mouth[3].src = 'assets/mouth/smiling.png';
mouth[4].src = 'assets/mouth/straight.png';
mouth[5].src = 'assets/mouth/teeth.png';

// Generates a probability and multiplies by range
function pickRandom(min, max) {
  return Math.floor(Math.random() * (max - min + 1)) + min;
}

var indexCache = [];
let prevIndex = [];
var skinIndex, eyesIndex, mouthIndex, emojiIndex;

function failUniqueThreshold(emojiIndex){
	for (let i = 0; i < prevIndex.length; i++) {
		let prevEmoji = prevIndex[i];
		if (emojiIndex[0] == prevEmoji[0] || emojiIndex[1] == prevEmoji[1] || emojiIndex[2] == prevIndex[2]){
			return true;
		}
	}
	prevIndex.push(emojiIndex);
	if (prevIndex.length > 3) {
    	prevIndex.shift();
	return false;
	}
}

function checkEmojiIndex(emojiIndex){
	if (indexCache.includes(emojiIndex)){
		return checkEmojiIndex(generateIndex());
	}
	if(failUniqueThreshold(emojiIndex)){
		return checkEmojiIndex(generateIndex());
	}
	return emojiIndex;
}

function generateIndex(){
	skinIndex = pickRandom(0, 6 - difficulty);
	eyesIndex = pickRandom(0, 5);
	mouthIndex = pickRandom(0, 5);
	emojiIndex = [skinIndex,eyesIndex,mouthIndex]
	return emojiIndex;
}


function generateEmoji(){
	var indexes = checkEmojiIndex(generateIndex());
	indexCache[indexCache.length] = indexes;
	
	var emojiSkin = new Image();
	emojiSkin.src = skin[skinIndex].src;
	var emojiEyes = new Image();
	emojiEyes.src = eyes[eyesIndex].src;
	var emojiMouth = new Image();
	emojiMouth.src = mouth[mouthIndex].src;
	
	var canvas = document.createElement('canvas');
	var context = canvas.getContext('2d');
	canvas.width = emojiSkin.width;
	canvas.height = emojiSkin.height;
	
	context.drawImage(emojiSkin, 0, 0);
	context.drawImage(emojiEyes, 0, 0);
	context.drawImage(emojiMouth, 0, 0);
	
	var emoji = new Image();
	emoji.src = canvas.toDataURL('image/png');
	return emoji.src;
}

function generateCard(cardGroup){
	let source = generateEmoji();
	var cardTemplate = `<div class='card' data-card="`+ cardGroup +`" data-moves = '0'>
    	<img class="card-front" src=` + source + `>
		<img class="card-back" src="assets/card/blue.png">
		</div>`;
	return cardTemplate.repeat(matches);
}

// Random event functions

function pickRandomEvent(randomEventProbability){	
	if (randomEventProbability > Math.random()){
		randomEvent(pickRandom(0,100));
	}
}

function randomEvent(event){
	if (event > 97){
		let errorCardTemplate = `<div class='card' data-card="error">
		<img class="card-front" src="assets/special/error.png">
		<img class="card-back" src="assets/card/blue.png">
		</div>`;
		container.innerHTML += errorCardTemplate.repeat(matches);
		return;
	}
	if (event > 92){	
		let shuffleCardTemplate = `<div class='card' data-card="shuffle">
    	<img class="card-front" src="assets/special/shuffle.png">
		<img class="card-back" src="assets/card/blue.png">
		</div>`;
		container.innerHTML += shuffleCardTemplate.repeat(matches);
		return;
	}
	if (event > 65){
		let timeCardTemplate = `<div class='card' data-card="time">
    	<img class="card-front" src="assets/special/time.png">
		<img class="card-back" src="assets/card/blue.png">
		</div>`;
		container.innerHTML += timeCardTemplate.repeat(matches);
		return;
	}
	if (event > 35){
		let moveCardTemplate = `<div class='card' data-card="moves">
		<img class="card-front" src="assets/special/moves.png">
		<img class="card-back" src="assets/card/blue.png">
		</div>`;
		container.innerHTML += moveCardTemplate.repeat(matches);
		return;
	} else {
		let scoreCardTemplate = `<div class='card' data-card="score">
		<img class="card-front" src="assets/special/points.png">
		<img class="card-back" src="assets/card/blue.png">
		</div>`;
		container.innerHTML += scoreCardTemplate.repeat(matches);
		return;
	}
}

function scoreEvent(){
	let points = 900 * Math.ceil((difficulty + 1)/2);
	score += points;
	let message = `<div>Score bonus!</div>
	<p style="font-size: 35px; text-shadow:2px 2px #383838;">+` + points + ` points</p>`;
	callOverlay(message,'aqua');
	const scoreboard = document.getElementById('score');
	scoreboard.innerHTML = score;
}

function timeEvent(){
	let extraTime = 30 * 1000 * Math.ceil((difficulty + 1)/3);
	finish += extraTime;
	let message = `<div>Time bonus!</div>
	<p style="font-size: 35px; text-shadow:2px 2px #383838;">+` + extraTime/1000 + ` seconds</p>`;
	callOverlay(message,'orange');
}

function moveEvent(){
	let extraMoves = 4 + Math.floor((difficulty + 1)/2);
	moveAllocation += extraMoves;
	let message = `<div>Move bonus!</div>
	<p style="font-size: 35px; text-shadow:2px 2px #383838;">+` + extraMoves + ` moves</p>`;
	callOverlay(message,'HotPink');
}

function shuffleEvent(){
	callOverlay('Shuffle!','pink');
	shuffleCards();
}

function errorEvent(){
	let message = `<div>Error!</div>
	<p style="font-size: 35px; text-shadow:2px 2px #383838;">Its all up to you...</p>`;
	callOverlay(message,'black');
	clearInterval(timer);
	errorEventMode = true;
	moves = 1;
	finish = new Date().getTime() + (60 * 60 * 1000);
	document.querySelector('.placeholder-text').classList.remove('hidden');
	document.getElementById("timer").classList.remove('hidden');

	timer = setInterval(function() {
	var now = new Date().getTime();
	var distance = finish - now;
	var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
	var seconds = Math.floor((distance % (1000 * 60)) / 1000);

	if (seconds < 10) {
	seconds = "0" + seconds;
	}
	document.getElementById("timer").innerHTML = minutes + ":" + seconds;
	if (moves > 15 + (difficulty * 2)){
		finish -= Math.floor(distance/10);
	}
	if (moves > 5 + difficulty){
		cards.forEach(card => {
			if ((0.1 * (difficulty + 1)) > Math.random()){
				let randomPos = Math.floor(Math.random() * (3 + difficulty));
				card.style.order = randomPos;
			}
		});
	}
	if (distance < 0) {
		clearInterval(timer);
		errorEventMode = false;
		document.getElementById("timer").innerHTML = "0:00";
		callOverlay("Times up!",'red')
		failLevel();
	}
	}, 1000);
}

// Game functionality

let cardsFlipped = false;
let lockBoard = false;
let firstCard, secondCard, thirdCard;


function flipCard() {
  if (lockBoard) return;
  if (this === firstCard) return;

  flipsfx.play();
  this.classList.add('-selected');
  this.setAttribute('data-moves', parseInt(this.getAttribute('data-moves')) + 1);
  

  if (matches == 2){
	if (!cardsFlipped){
		cardsFlipped = true;
		firstCard = this;
		return;
	}

	secondCard = this;
	checkForMatch();
  }
  
  if (matches == 3){
	if (!cardsFlipped){
		if (firstCard != null){
			cardsFlipped = true;
			secondCard = this;
			return;
		}
		else {
			firstCard = this;
			return;
		}
	}

	thirdCard = this;
	checkForMatch();
  }
}


function checkForMatch() {
	if (matches == 2){
		isMatch = firstCard.dataset.card === secondCard.dataset.card;
		isMatch ? disableCards() : unflipCards();
	}
	else {
		isMatch = firstCard.dataset.card === secondCard.dataset.card && secondCard.dataset.card === thirdCard.dataset.card
		isMatch ? disableCards() : unflipCards();
	}
}

function addScore() {
	var baseScore = 200 * matches;
	if (errorEventMode){
		baseScore = 350 * Math.ceil((difficulty + 1) / 2);
	}
	let moveBonus = 0;
	if (matches == 2){
		var cardMoves = parseInt(firstCard.getAttribute('data-moves')) + parseInt(secondCard.getAttribute('data-moves'));
		moveBonus =  1 + (matches / cardMoves);
	} else {
		var cardMoves = parseInt(firstCard.getAttribute('data-moves')) + parseInt(secondCard.getAttribute('data-moves')) + parseInt(thirdCard.getAttribute('data-moves'));
		moveBonus =  1 + (matches / cardMoves);
	}
	let bonusScore = Math.floor(20 * ((difficulty + 1) / 2) * moveBonus);
	score += (baseScore + bonusScore);
	if (highScores[level-1] > 0){
		if (score > highScores[level-1]){
			document.querySelector('.content-box').style.backgroundColor = "rgb(255,215,0,0.9)";
		}
	}
	const scoreboard = document.getElementById('score');
	scoreboard.innerHTML = score;
	return;
}

function disableCards() {
	if (matches == 2){
		if (firstCard.getAttribute('data-card') == "score"){
			bonussfx.play();
			scoreEvent();
		} else if (firstCard.getAttribute('data-card') == "time") {
			bonussfx.play();
			timeEvent();
		} else if (firstCard.getAttribute('data-card') == "moves") {
			bonussfx.play();
			moveEvent();
		} else if (firstCard.getAttribute('data-card') == "shuffle") {
			shufflesfx.play();
			shuffleEvent();
		} else if (firstCard.getAttribute('data-card') == "error") {
			errorsfx.play();
			errorEvent();
		} else {
			matchedsfx.play();
			addScore();
		}
		firstCard.removeEventListener('click', flipCard);
  		secondCard.removeEventListener('click', flipCard);
		firstCard.classList.add('-matched');
  		secondCard.classList.add('-matched');
		moves += 1;
		moveAllocation -= 1;
		document.getElementById("moves").innerHTML = moveAllocation;
	} else {
		if (firstCard.getAttribute('data-card') == "score"){
			bonussfx.play();
			scoreEvent();
		} else if (firstCard.getAttribute('data-card') == "time") {
			bonussfx.play();
			timeEvent();
		} else if (firstCard.getAttribute('data-card') == "moves") {
			bonussfx.play();
			moveEvent();
		} else if (firstCard.getAttribute('data-card') == "shuffle") {
			shufflesfx.play();
			shuffleEvent();
		} else if (firstCard.getAttribute('data-card') == "error") {
			errorsfx.play();
			errorEvent();
		} else {
			matchedsfx.play();
			addScore();
		}
		firstCard.removeEventListener('click', flipCard);
		secondCard.removeEventListener('click', flipCard);
		thirdCard.removeEventListener('click', flipCard);
		firstCard.classList.add('-matched');
		secondCard.classList.add('-matched');
		thirdCard.classList.add('-matched');
		moves += 1;
		moveAllocation -= 1;
		document.getElementById("moves").innerHTML = moveAllocation;
	}
	if (document.querySelectorAll('.-matched').length == cards.length){
		endLevel();
		return;
	}
	if (moveAllocation == 0){
		callOverlay("Out of moves!",'red')
		failLevel();
		return;
	}
	moves += 1;
  	resetBoard();
}

function unflipCards() {
  lockBoard = true;
  moves += 1;
  moveAllocation -= 1;
  document.getElementById("moves").innerHTML = moveAllocation;
  if (moveAllocation == 0){
		callOverlay("Out of moves!",'red')
		failLevel();
		return;
	}
  setTimeout(() => {
	if (matches == 2){
		firstCard.classList.remove('-selected');
    	secondCard.classList.remove('-selected');
	}
	else {
		firstCard.classList.remove('-selected');
		secondCard.classList.remove('-selected');
		thirdCard.classList.remove('-selected');
	}
	unflipsfx.play();
    resetBoard();
  }, 1000);
}

function resetBoard() {
  [cardsFlipped, lockBoard] = [false, false];
  [firstCard, secondCard, thirdCard] = [null, null, null];
}

function shuffleCards() {
  cards.forEach(card => {
    let randomPos = Math.floor(Math.random() * cards.length);
    card.style.order = randomPos;
  });
}

function setTimer(timeAllocated){
	if (timeAllocated > 0){
		finish = new Date().getTime() + (timeAllocated * 1000);
		document.querySelector('.placeholder-text').classList.remove('hidden');
		document.getElementById("timer").classList.remove('hidden');

		timer = setInterval(function() {
		var now = new Date().getTime();
		var distance = finish - now;
		var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
		var seconds = Math.floor((distance % (1000 * 60)) / 1000);

		// pad with leading zero when seconds is single digit
		if (seconds < 10) {
		seconds = "0" + seconds;
		}
		document.getElementById("timer").innerHTML = minutes + ":" + seconds;

		if (distance < 0) {
		clearInterval(timer);
		document.getElementById("timer").innerHTML = "0:00";
		callOverlay("Times up!",'red')
		failLevel();
		}
		}, 1000);
	} else {
		return;
	}
}

// Event related functions

var data, gameLength;
function loadLevelParams(){
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			data = JSON.parse(this.responseText);
			gameLength = Object.keys(data).length;
		}
	};
	xhttp.open("GET", "levelParams.json", true);
	xhttp.send();
	for (let i = 0; i < gameLength; i++) {
	  levelScores[i] = 0;
	}
}

var main, main2, main3, timeout;
var timeSoundd;
function loadMusic(){
	playback = true;
	main = new Howl({src: ['assets/audio/background/main.wav'], loop: true, volume: 0.3});
	main2 = new Howl({src: ['assets/audio/background/main2.wav'], loop: true, volume: 0.5});
	main3 = new Howl({src: ['assets/audio/background/main3.wav'], loop: true, volume: 0.5});
	main3 = new Howl({src: ['assets/audio/background/main3.wav'], loop: true, volume: 0.5});
	timeout = new Howl({src: ['assets/audio/background/timeout2.wav'], loop: true, volume: 0.5});
}

var flipsfx, unflipsfx, bonussfx, shufflesfx, clicksfx, errorsfx, matchedsfx, levelcompletesfx, gamecompletesfx, failsfx;
function loadSounds(){
	flipsfx = new Howl({src: ['assets/audio/sfx/cardflip.mp3'], volume: 0.5});
	unflipsfx = new Howl({src: ['assets/audio/sfx/unflip.mp3'], volume: 0.3});
	bonussfx = new Howl({src: ['assets/audio/sfx/bonus.mp3'], volume: 0.8});
	shufflesfx = new Howl({src: ['assets/audio/sfx/shuffle.mp3'], volume: 0.5});
	clicksfx = new Howl({src: ['assets/audio/sfx/click.mp3'], volume: 0.5});
	errorsfx = new Howl({src: ['assets/audio/sfx/error.mp3'], volume: 0.5});
	matchedsfx = new Howl({src: ['assets/audio/sfx/matched.wav'], volume: 0.1, rate: 0.9});
	levelcompletesfx = new Howl({src: ['assets/audio/sfx/levelcomplete.mp3'], volume: 0.1});
	gamecompletesfx = new Howl({src: ['assets/audio/sfx/gamecomplete.mp3'], volume: 0.8});
	failsfx = new Howl({src: ['assets/audio/sfx/faillevel.mp3'], volume: 0.3});
}

// Load parameters on page load
window.addEventListener("load", function() {
  loadLevelParams();
  loadMusic();
  loadSounds();
});


function callOverlay(message,textColor){
	overlay = document.querySelector('.overlay');
	overlay.style.color = textColor;
	overlay.innerHTML = message;
	overlay.classList.remove('inactive');
	setTimeout(() => {
	overlay.classList.add('inactive');
  }, 3000);
}

function failLevel() {
	cards.forEach(card => card.classList.add('-selected'));
	failsfx.play();
	errorEventMode = false;
	clearInterval(timer);
	indexCache = [];
	prevIndex = [];
	setTimeout(() => {
	cards.forEach(card => card.classList.remove('-selected'));
	}, 5000);
	setTimeout(() => {
	document.querySelector('.content-box').style.backgroundColor = "rgb(128,128,128,0.9)";
	container.innerHTML = '';
	moves = 0;
	score = 0;
	document.getElementById('score').innerHTML = score;
	loadLevel();
	}, 6000);
}

function loadLevel() {
	indexCache = [];
	prevIndex = [];
	let levelParams = data[level];
	matches = parseInt(levelParams["matches"]);
	difficulty = parseInt(levelParams["difficulty"]);
	timeAllocation = parseInt(levelParams["timeAllocation"]);
	randomEventProbability = parseInt(levelParams["randomEventProbability"]);
	moveAllocation = parseInt(levelParams["moveAllocation"]);
	document.getElementById("level").innerHTML = level;
	document.getElementById('moves').innerHTML = moveAllocation;
	generateBoard(levelParams["cards"]);
	shuffleCards();
	resetBoard();
	setTimer(timeAllocation);
	cards.forEach(card => card.addEventListener('click', flipCard));
}

function endLevel(){
	if (level < gameLength){
		errorEventMode = false;
		clearInterval(timer);
		callOverlay("Level Complete!",'lime');
		levelcompletesfx.play();
		levelScores[level-1] = score;
		indexCache = [];
		prevIndex = [];
		setTimeout(() => {
		document.querySelector('.content-box').style.backgroundColor = "rgb(128,128,128,0.9)";
		container.innerHTML = '';
		moves = 0;
		score = 0;
		document.getElementById('score').innerHTML = score;
		level += 1;
		loadLevel();
  		}, 1000);
	} else{
		errorEventMode = false;
		clearInterval(timer);
		gamecompletesfx.play();
		levelScores[level-1] = score;
		indexCache = [];
		prevIndex = [];
		document.querySelector('.prompt').classList.remove('hidden');
	}
}

function startGame() {
	loadLevel();
	playMusic();
	start.style.display = 'none';
	container.classList.remove('hidden');  
}

function restartGame(){
	document.querySelector('.prompt').classList.add('hidden');
	container.innerHTML = '';
	levelScores = [];
	moves = 0;
	score = 0;
	level = 1;
	document.getElementById('score').innerHTML = score;
	loadLevel();
}

function playMusic(){
	main.play();
	main.fade(0, 0.3, 4000);
	const playlist = [
	function() {
		main.fade(0.3, 0, 4000);
		setTimeout(() => {
		main.stop();
		main2.play();
		main2.fade(0, 0.5, 4000);
		}, 4000);	
	},
	function() {
		main2.fade(0.5, 0, 4000)
		setTimeout(() => {
		main2.stop()
		main3.play();
		main3.fade(0, 0.5, 4000);
		}, 4000);	
	},
	function() {
		main3.fade(0.5, 0, 4000);
		setTimeout(() => {
		main3.stop();
		main.play();
		main.fade(0, 0.3, 4000);
		}, 4000);
	}
	];
	let playlistIndex = 0;
	setInterval(function() {
	playlist[playlistIndex]();
	playlistIndex = (playlistIndex + 1) % playlist.length;
	}, 1000 * 60 * 2.5);
}

function toggleSoundEffects(){
	if (sfx){
		document.getElementById('sfx').src = "assets/game/sfxoff.png"
		flipsfx.mute(true);
		unflipsfx.mute(true);
		bonussfx.mute(true);
		shufflesfx.mute(true);
		clicksfx.mute(true);
		errorsfx.mute(true);
		matchedsfx.mute(true);
		levelcompletesfx.mute(true);
		gamecompletesfx.mute(true);
		failsfx.mute(true);
		sfx = false;
	} else {
		document.getElementById('sfx').src = "assets/game/sfxon.png"
		flipsfx.mute(false);
		unflipsfx.mute(false);
		bonussfx.mute(false);
		shufflesfx.mute(false);
		clicksfx.mute(false);
		errorsfx.mute(false);
		matchedsfx.mute(false);
		levelcompletesfx.mute(false);
		gamecompletesfx.mute(false);
		failsfx.mute(false);
		sfx = true;
	}
}

function toggleMusic(){
	if (music){
		document.getElementById('music').src = "assets/game/musicoff.png"
		main.mute(true);
		main2.mute(true);
		main3.mute(true);
		music = false;
	} else {
		document.getElementById('music').src = "assets/game/musicon.png"
		main.mute(false);
		main2.mute(false);
		main3.mute(false);
		music = true;
	}
}

function exportScores(){
	var xhttp = new XMLHttpRequest();
	const scores = new FormData();
	var scoreArray = JSON.stringify(levelScores);
	createCookie('scores', scoreArray);
	scores.append("levelScores", scoreArray);
	xhttp.open("POST", "leaderboard.php");
	xhttp.send(scores);
	window.location.href = "leaderboard.php";
}

var highScores = [];
$.ajax({
  url: 'fetchScores.php',
  type: 'GET',
  dataType: 'json',
  success: function(response) {
    $.each(response, function(table, score) {
		if (score == null){
			highScores[highScores.length] = 0;
		} else{
			highScores[highScores.length] = score;
		}
    });
  },
});

start.addEventListener('click', startGame);