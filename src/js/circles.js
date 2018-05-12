var circles = [];
var maxspeed = 3;
var lastCircle = null;
var cnv;

function setup() {
	cnv = createCanvas(windowWidth, windowHeight);
	cnv.parent('canvCnt');
}

function draw() {
	background(0);
		lastCircle = new Circle();
		circles.push(lastCircle);
		for(let i=0; i<circles.length-1; i++){
			if(circles[i].isDissolved()){
				circles.splice(i,1);
			}else{
				circles[i].update();
			}
		}
}

function mouseMoved(){
	lastCircle.enlarge();
}

function windowResized(){
	cnv.resize(windowWidth,windowHeight);
}

class Circle {
	constructor(){
		this.r = 255;
		this.g = 255;
		this.b = 255;
		this.x = mouseX;
		this.y = mouseY;
		this.xspeed = random(-maxspeed,maxspeed);
		this.yspeed = random(-maxspeed,maxspeed);
		this.stroke = 2;
		this.width = 40;
		this.a = 200;
	}
	
	update(){
		this.x += this.xspeed;
		this.y += this.yspeed;
		this.a *= 0.999;
		this.width *= 0.9;
		this.stroke *= 0.9;
		this.show();
	}
	
	show(){
		let c = color(this.r,this.g,this.b, this.a);
		stroke(c);
		strokeWeight(this.stroke);
		fill(0,this.a);
		ellipse(this.x,this.y,this.width);
	}
	
	enlarge(){
		this.r = random(0,255);
		this.g = random(0,255);
		this.b = random(0,255);
		this.a = 100;
		this.stroke *= 4;
		this.width *= 1.2;
		this.show();
	}
	
	isDissolved(){
		return this.width<1;
	}
}