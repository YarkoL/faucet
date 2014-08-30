Heart.Preloader = function(game) {};
Heart.Preloader.prototype = {
	preload: function() {
		this.game.stage.backgroundColor = '#16181a';
		//this.preloadBg = this.add.sprite((320-297)/2, (480-145)/2, 'preloaderBg');
		this.preloadBar = this.add.sprite((320-158)/2, (480-50)/2, 'preloaderBar');
		this.game.load.image('button-continue', 'assets/continue.png');
		this.game.load.image('button-cashout', 'assets/cashout.png');
		this.game.load.image('button-exit', 'assets/exit.png');
		this.game.load.image('heart', 'assets/heart.png');
		this.game.load.image('pipe', 'assets/pipe.png');
		this.game.load.image('grass', 'assets/grass.png');
		this.game.load.image('clouds', 'assets/clouds.png');
		this.game.load.image('howto', 'assets/howto.png');
		this.game.load.audio('bounce', 'assets/bounce.mp3');  
	},
	create: function() {
		this.game.state.start('HowTo');
	}
};