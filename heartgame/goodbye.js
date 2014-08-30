Heart.Bye = function(game) {};
Heart.Bye.prototype = {
	preload: function() {
		this.game.stage.backgroundColor = '#01a8eb';
	},
	create: function() {
		//this.game.result = new Text
	    this.game.add.text(30,20,
	    	"Sent " + gAmount.toFixed(1) + " to your address. Click the button below to view the transaction in the block explorer (Note that it takes a few minutes to show up)",
	    	{font:"20px Arial", fill: "#ffffff",wordWrap: true, wordWrapWidth: 200, align: "center"});
		this.exit = this.game.add.button((this.game.world.width - 146)/2, 300, 'button-exit', this.exitGame, this);
	},
	
	exitGame: function() {
		txlink = this.game.txdata.replace(/"/g, "");
		window.open("http://162.243.18.102/tx/" + txlink,"_self")
	}
};