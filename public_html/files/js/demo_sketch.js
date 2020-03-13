let video;
let poseNet;
let msg="Allow CAMERA, Model loading...";
let noseX = 0;
let noseY = 0;
let leftEyeX=0;                  //1 
let leftEyeY=0;                  //1
let rightEyeX=0;                 //2
let rightEyeY=0;                 //2 
let leftEarX=0;                  //3
let leftEarY=0;                  //3 
let rightEarX=0;                 //4
let rightEarY=0;                 //4 
let leftShoulderX=0;             //5
let leftShoulderY=0;             //5 
let rightShoulderX=0;            //6
let rightShoulderY=0;            //6 
let leftElbowX=0;                //7
let leftElbowY=0;                //7 
let rightElbowX=0;               //8
let rightElbowY=0;               //8 
let leftWristX=0;                //9
let leftWristY=0;                //9 
let rightWristX=0;               //10
let rightWristY=0;               //10
let leftHipX=0;                  //11
let leftHipY=0;                  //11
let rightHipX=0;                 //12
let rightHipY=0;                 //12
let leftKneeX=0;                 //13
let leftKneeY=0;                 //13
let rightKneeX=0;                //14
let rightKneeY=0;                //14
let leftAnkleX=0;                //15
let leftAnkleY=0;                //15
let rightAnkleX=0;               //16
let rightAnkleY=0;               //16

function setup() {
  let dCanvas = createCanvas(640, 480);
  dCanvas.parent('demoContainer');
  video = createCapture(VIDEO);
  video.hide();
  //console.log(ml5);
  poseNet= ml5.poseNet(video,modelLoaded);
  poseNet.on('pose',gotPoses);
}

function gotPoses(poses){
  console.log(poses);
  if(poses.length>0){
    let oldNoseX=poses[0].pose.keypoints[0].position.x;
    let oldNoseY=poses[0].pose.keypoints[0].position.y;
    noseX=lerp(noseX,oldNoseX,0.5);
    noseY=lerp(noseY,oldNoseY,0.5);
  leftEyeX=lerp(leftEyeX,poses[0].pose.keypoints[1].position.x,0.5);  leftEyeY=lerp(leftEyeY,poses[0].pose.keypoints[1].position.y,0.5);
    
     rightEyeX=lerp(rightEyeX,poses[0].pose.keypoints[2].position.x,0.5);  
rightEyeY=lerp(rightEyeY,poses[0].pose.keypoints[2].position.y,0.5); 
    
    leftEarX=Math.round(lerp(leftEarX,poses[0].pose.keypoints[3].position.x,0.5));
    
    //leftEarX=Math.round(poses[0].pose.keypoints[3].position.x);
//leftEarY=Math.round(poses[0].pose.keypoints[3].position.y);
    
leftEarY=Math.round(lerp(leftEarY,poses[0].pose.keypoints[3].position.y,0.5));  
rightEarX=Math.round(lerp(rightEarX,poses[0].pose.keypoints[4].position.x,0.5)); 
rightEarY=Math.round(lerp(rightEarY,poses[0].pose.keypoints[4].position.y,0.5));
      leftShoulderX=Math.round(lerp(leftShoulderX,poses[0].pose.keypoints[5].position.x,0.5));
leftShoulderY=Math.round(lerp(leftShoulderY,poses[0].pose.keypoints[5].position.y,0.5));
rightShoulderX=Math.round(lerp(rightShoulderX,poses[0].pose.keypoints[6].position.x,0.5));
rightShoulderY=Math.round(lerp(rightShoulderY,poses[0].pose.keypoints[6].position.y,0.5));


leftElbowX=Math.round(lerp(leftElbowX,poses[0].pose.keypoints[7].position.x,0.5)); 
leftElbowY=Math.round(lerp(leftElbowY,poses[0].pose.keypoints[7].position.y,0.5)); 
rightElbowX=Math.round(lerp(rightElbowX,poses[0].pose.keypoints[8].position.x,0.5));
rightElbowY=Math.round(lerp(rightElbowY,poses[0].pose.keypoints[8].position.y,0.5));
    
    leftWristX=Math.round(lerp(leftWristX,poses[0].pose.keypoints[9].position.x,0.5));
leftWristY=Math.round(lerp(leftWristY,poses[0].pose.keypoints[9].position.y,0.5)); 
rightWristX=Math.round(lerp(rightWristX,poses[0].pose.keypoints[10].position.x,0.5));
rightWristY=Math.round(lerp(rightWristY,poses[0].pose.keypoints[10].position.y,0.5));
    
    leftHipX=Math.round(lerp(leftHipX,poses[0].pose.keypoints[11].position.x,0.5));   
leftHipY=Math.round(lerp(leftHipY,poses[0].pose.keypoints[11].position.y,0.5)); 
rightHipX=Math.round(lerp(rightHipX,poses[0].pose.keypoints[12].position.x,0.5));  
rightHipY=Math.round(lerp(rightHipY,poses[0].pose.keypoints[12].position.y,0.5)); 

leftKneeX=Math.round(lerp(leftKneeX,poses[0].pose.keypoints[13].position.x,0.5));  
leftKneeY=Math.round(lerp(leftKneeY,poses[0].pose.keypoints[13].position.y,0.5));  
rightKneeX=Math.round(lerp(rightKneeX,poses[0].pose.keypoints[14].position.x,0.5)); 
rightKneeY=Math.round(lerp(rightKneeY,poses[0].pose.keypoints[14].position.y,0.5));
leftAnkleX=Math.round(lerp(leftAnkleX,poses[0].pose.keypoints[15].position.x,0.5)); 
leftAnkleY=Math.round(lerp(leftAnkleY,poses[0].pose.keypoints[15].position.y,0.5)); 
rightAnkleX=Math.round(lerp(rightAnkleX,poses[0].pose.keypoints[16].position.x,0.5));
rightAnkleY=Math.round(lerp(rightAnkleY,poses[0].pose.keypoints[16].position.y,0.5));




    
  }
  
}

function modelLoaded(){
  console.log('Model Loaded');
  msg="";
}

function draw() {
  //background(0,0,0);
  background(255);
  image(video,0,0);
  //filter(THRESHOLD);
  fill(255,0,0);
  noStroke();
  text(msg, 100, 100);
  //ellipse(noseX,noseY,20,20);
  //ellipse(leftEyeX,leftEyeY,20,20);
  strokeWeight(4);
  stroke(255,0,0);
  point(noseX,noseY);
  point(leftEyeX,leftEyeY);
  point(rightEyeX,rightEyeY);
  //stroke(51);
  line(leftEarX,leftEarY,rightEarX,rightEarY);
  noStroke();
  text("L("+leftEarX+","+leftEarY+")",leftEarX,leftEarY);
  text("R("+rightEarX+","+rightEarY+")",rightEarX,rightEarY);
  stroke(255,0,0);
  
  let neckX=(leftShoulderX+rightShoulderX)/2;
  let neckY=(leftShoulderY+rightShoulderY)/2;
  line(leftEarX,leftEarY,neckX,neckY);
  line(rightEarX,rightEarY,neckX,neckY);
  line(leftShoulderX,leftShoulderY,rightShoulderX,rightShoulderY);
  noStroke();
  text("L("+leftShoulderX+","+leftShoulderY+")",leftShoulderX,leftShoulderY);
  text("R("+rightShoulderX+","+rightShoulderY+")",rightShoulderX,rightShoulderY);
  stroke(255,0,0);
  
  line(leftHipX,leftHipY,rightHipX,rightHipY);
 
  noStroke();
  text("L("+leftHipX+","+leftHipY+")",leftHipX,leftHipY);
  text("R("+rightHipX+","+rightHipY+")",rightHipX,rightHipY);
  stroke(255,0,0);
  
  line(leftShoulderX,leftShoulderY,leftHipX,leftHipY);
  line(rightShoulderX,rightShoulderY,rightHipX,rightHipY);

  line(leftShoulderX,leftShoulderY,leftElbowX,leftElbowY);
  line(leftElbowX,leftElbowY,leftWristX,leftWristY);
  noStroke();
  text("L("+leftElbowX+","+leftElbowY+")",leftElbowX,leftElbowY);
  text("L("+leftWristX+","+leftWristY+")",leftWristX,leftWristY);
  stroke(255,0,0);
  
  line(rightShoulderX,rightShoulderY,rightElbowX,rightElbowY);
  line(rightElbowX,rightElbowY,rightWristX,rightWristY);
  noStroke();
  text("R("+rightElbowX+","+rightElbowY+")",rightElbowX,rightElbowY);
  text("R("+rightWristX+","+rightWristY+")",rightWristX,rightWristY);
  stroke(255,0,0);
  
  line(leftHipX,leftHipY,leftKneeX,leftKneeY);
  line(leftKneeX,leftKneeY,leftAnkleX,leftAnkleY);
  noStroke();
  text("L("+leftKneeX+","+leftKneeY+")",leftKneeX,leftKneeY);
  text("L("+leftAnkleX+","+leftAnkleY+")",leftAnkleX,leftAnkleY);
  stroke(255,0,0);
  
  line(rightHipX,rightHipY,rightKneeX,rightKneeY);
  line(rightKneeX,rightKneeY,rightAnkleX,rightAnkleY);
  noStroke();
  text("R("+rightKneeX+","+rightKneeY+")",rightKneeX,rightKneeY);
  text("R("+rightAnkleX+","+rightAnkleY+")",rightAnkleX,rightAnkleY);
  stroke(255,0,0);
  //strokeWeight(4);
  //stroke(51);
  noStroke();
  text(year()+"-"+month()+"-"+day()+" "+hour()+":"+minute()+":"+second(), 10, 370);
  stroke(255,0,0);
}