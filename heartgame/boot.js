var Heart = {};
Heart.Boot = function(game) {
	
};
Heart.Boot.prototype = {
	preload: function() {
		//this.load.image('preloaderBg', 'img/loading-bg.png');
		this.load.image('preloaderBar', 'assets/loading-bar.png');
	},
	create: function() {
		//this.getData();
		this.game.input.maxPointers = 1;
		this.game.scale.scaleMode = Phaser.ScaleManager.SHOW_ALL;
		this.game.scale.pageAlignHorizontally = true;
		this.game.scale.pageAlignVertically = true;
		//this.game.stage.scale.setScreenSize(true);
		this.game.scale.refresh();
		
		this.game.state.start('Preloader');
	},
	getData: function() {
		var req = new XMLHttpRequest(); //New request object
		req.onload = function() {
       		console.log(this);
		}        
		req.open("get", "get-data.php", true);
		req.send();
	}
};