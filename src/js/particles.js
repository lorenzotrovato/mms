var dim = 5;
var bdim = 20;
var speed = 1;
var npart = 500;
var range = 40;
var particles = [];
var cnv;
function setup() {
	cnv = createCanvas(windowWidth, windowHeight);
	cnv.parent('canvCnt');
	let dis = floor(windowWidth*windowHeight)/(npart);
	let xm = dis;
	let ym = dis;
	for(let i=0; i<npart; i++){
		if(xm < windowWidth){
			xm += dis;
		}else{
			xm -= windowWidth;
			ym += dis;
		}
		var c;
		switch(round(random(0,2))){
			case 0: c = color(80);
				break;
			case 1: c = color(0,0,240);
				break;
			default: c = color(240,0,0);
		}
		particles.push(new Particle(c, xm, ym));
	}
}

function draw() {
	background(255);
	for(let i=0; i<particles.length; i++){
		particles[i].update();
	}
}

class Particle {
	constructor(color, x, y){
		this.dim = dim;
		this.color = color;
		this.x = x;
		this.y = y;
		this.xspeed = random(-speed,speed);
		this.yspeed = random(-speed,speed);
		this.show();
	}
	
	show(){
		noStroke();
		fill(this.color);
		ellipse(this.x,this.y,this.dim);
	}
	
	update(){
		this.x += this.xspeed;
		this.y += this.yspeed;
		this.underMouse()
		if(this.x > windowWidth+dim){
			this.x -= windowWidth+dim;
		}else if(this.x < 0-dim){
			this.x += windowWidth+dim;
		}
		if(this.y > windowHeight+dim){
			this.y -= windowHeight+dim;
		}else if(this.y < 0-dim){
			this.y += windowHeight+dim;
		}
		if(this.dim>dim){
			this.dim *= 0.9;
		}
		this.show();
	}
	
	underMouse(){
		if(mouseX > 0 && mouseY > 0){
			if(abs(this.x-mouseX)<=range && abs(this.y-mouseY)<=range){
				this.dim = bdim;
			}
		}
	}
}