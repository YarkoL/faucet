Heart.HowTo = function(game) {};
Heart.HowTo.prototype = {
	create: function() {
		this.showHowto();
	},
	showHowto: function() {
		this.buttonContinue = this.game.add.button(0, 0, 'howto', this.startGame, this);
	},
	startGame: function() {
		this.game.state.start('Game');
	}
};