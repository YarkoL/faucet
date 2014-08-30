

Heart.Game = function(game) {
	initScore = 0;
};
Heart.Game.prototype = {
	preload: function() {
		this.game.stage.backgroundColor = '#01a8eb';
	},

	create: function() {
		clouds = this.game.add.tileSprite(0, 225, 512, 64, 'clouds');
		this.game.physics.startSystem(Phaser.Physics.ARCADE);
	    this.heart = this.game.add.sprite(100,245, 'heart');
	    this.game.physics.arcade.enable(this.heart);
	    this.heart.anchor.setTo(-0.2, 0.5);  
	    this.heart.body.gravity.y = 1000;
	    this.heart.body.collideWorldBounds = true;
	    this.heart.body.bounce.y = 0.4;
	    this.heart.body.setSize(40,40,0,0);
		
		this.pipes = this.game.add.group();
		this.pipes.enableBody = true;
		this.pipes.alive = true;
		this.pipes.createMultiple(20, 'pipe');
		this.timer = this.game.time.events.loop(2500, this.addPipeRow, this);

		this.game.score = initScore;
		this.labelScore = this.game.add.text(20,20,this.game.score.toFixed(1).toString(),{font:"30px Arial", fill: "#ffffff"});

		grass = this.game.add.tileSprite(0, 458, 512, 32, 'grass');

		bounceSound = this.game.add.audio('bounce');  
		
	},

	update: function() {
		if (this.game.input.activePointer.isDown)
        	this.jump();
		clouds.tilePosition.x -= 0.1;
		this.game.physics.arcade.collide(this.heart, this.pipes, this.hitPipe, null, this);

		if (this.heart.alive == true) {
			grass.tilePosition.x -= 2;
			//clouds.tilePosition.x -= 0.1;
		}
		if (this.heart.angle < 20)  
    		this.heart.angle += 1;
    	
	},

	jump: function() {
		if (this.heart.alive == false)
        	return;
		this.heart.body.velocity.y = -200;
		var anim = this.game.add.tween(this.heart);
		anim.to({angle: -20}, 100);
        anim.start();
	},

	addPipe: function(x,y) {
		var pipe = this.pipes.getFirstDead();
		pipe.reset(x,y);
		pipe.body.velocity.x = -200;
		pipe.checkWorldBounds = true;
		pipe.outOfBoundsKill = true;
		pipe.body.immovable = true;
	},

	addPipeRow: function() {
		var hole = Math.floor(Math.random() * 8) + 1;
		for (var i = 0; i < 8; i++) {
			if (i != hole && i != hole + 1 && i != hole - 1)
				this.addPipe(400, i * 60);
		}
		this.game.score += 0.1;
		this.labelScore.text = this.game.score.toFixed(1);
	},

	hitPipe: function() {  
    
    	if (this.heart.alive == false)
        	return;
    	/*
    	this.game.time.events.add(2000, function() {
   		this.game.add.tween(hitText).to({y: 0}, 1500, Phaser.Easing.Linear.None, true);
    	this.game.add.tween(hitText).to({alpha: 0}, 1500, Phaser.Easing.Linear.None, true);
		}, this);
		*/
    	this.heart.alive = false;
    	bounceSound.play();
   	 	this.game.time.events.remove(this.timer);

        // Go through all the pipes, and stop their movement
    	this.pipes.forEachAlive(function(p){
        	p.body.velocity.x = 0;
    	}, 	this);
    	this.game.score -= 0.5;
    	if (this.game.score < 0) this.game.score = 0;
		this.labelScore.text = this.game.score.toFixed(1);
		initScore = this.game.score;
		this.playContinue = this.game.add.button((this.game.world.width - 146)/2, 100, 'button-continue', this.restartGame, this);
		if (this.game.score > 0) {
			this.playCashout = this.game.add.button((this.game.world.width - 146)/2, 250, 'button-cashout', this.cashout, this);
		} 
	},

	restartGame: function() {
		//this.game.time.events.remove(this.timer);
		this.game.state.start('Game');

	},

	cashout: function() {
		var xhr;
		var that = this;
		gAmount = this.game.score;
        if (window.XMLHttpRequest) xhr = new XMLHttpRequest(); // all browsers 
        else xhr = new ActiveXObject("Microsoft.XMLHTTP");     // for IE
		xhr.onload = function() {
       		that.game.txdata = this.responseText;
       		that.game.state.start('Bye');
		}        
		xhr.open("POST", "gamedrip.php", true);
		xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xhr.send("amount=" + this.game.score.toFixed(1));
		
	}
};


