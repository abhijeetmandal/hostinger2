<div id='myContainer'></div>
<script>    
    let mobilenet;
    let classifier;
    let video;
    let label = 'loading model';
    let happyButton;
    let sadButton;
    let trainButton;
    let myCanvas;
    let seconds=0;
    let uptime=0;
    let countdown=5;
    let err=false;
    let interval;


    function modelReady() {
      console.log('Model is ready!!!');
      label='Model is getting ready!!!';
      classifier = mobilenet.classification(video, videoReady);
    }

     function customModelReady() {
       console.log('Custom Model is ready!!!');
       label='Custom Model is getting ready!!!';
       document.getElementById("category").value="<?= strtolower($challenge->group); ?>";
       document.getElementById("challenge").value="<?= $challenge->name; ?>";
       uptime="<?= $challenge->data['uptime']; ?>";
       setTimeout(addCountDown,1000);
       classifier.classify(gotResults);
     }

    function videoReady() {
      console.log('Video is ready!!!');
      label='Video is getting ready!!!';
      //classifier.load('model6.json', customModelReady);
              
      classifier.load('<?= $model; ?>', customModelReady);

    }

    function setup() {
      //createCanvas(320, 270);
      myCanvas = createCanvas(480, 390);
      myCanvas.parent('myContainer');
      video = createCapture(VIDEO);
      video.elt.setAttribute('playsinline', '');
      video.hide();
      background(0);

      console.log('ml5 version:', ml5.version);
      label='ml5 version:'+ml5.version;
      mobilenet = ml5.featureExtractor('MobileNet', modelReady);
      

      //below 2 lines bad
      //mobilenet = ml5.imageClassifier('MobileNet', video, modelReady);
      //classifier = mobilenet.predict(video, videoReady);

//      happyButton = createButton('happy');
//      happyButton.mousePressed(function () {
//        console.log('addImage happy!!!');
//        label='addImage happy';
//        classifier.addImage('happy');
//      });
//
//      sadButton = createButton('sad');
//      sadButton.mousePressed(function () {
//        console.log('addImage sad!!!');
//        label='addImage sad';
//        classifier.addImage('sad');
//      });
//
//      trainButton = createButton('train');
//      trainButton.mousePressed(function () {
//        classifier.train(whileTraining);
//      });
//
//      saveButton = createButton('save');
//      saveButton.mousePressed(function () {
//        classifier.save();
//      });
    }

    function draw() {
      background(0);
      image(video, 0, 0, 480, 360);
      fill(255,0,0);
      textSize(16);
      //text(label, 10, height - 10);
      if(countdown>=0 && seconds==0){
        text("Starts in "+countdown, 350, 20);
      }
      text(seconds +" sec", 10, 20);
      text(label, 10, 380);
    }


    function whileTraining(loss) {
      if (loss == null) {
        console.log('Training Complete');
        label='Training Complete';
        classifier.classify(gotResults);
      } else {
        console.log(loss);
        label='loss: '+loss;
      }
    }


    function gotResults(error, results) {
      if (error) {
        console.error(error);
        label=error;
        err=true;
        label='Device not suppoted, for optimal performance use Chrome for Andriod.';
        return false;
      } else {
          label = results[0].label+","+nf(results[0].confidence, 0, 2);
            if(results[0].label=='bad plank' && nf(results[0].confidence, 0, 2)>0.50 && seconds>0){
              document.getElementById("token").value=seconds;
              document.getElementById("myChallenge").submit();
            }
          classifier.classify(gotResults);  
        
        //console.log(results);
        //label = results[0].label+","+nf(results[0].confidence, 0, 2)+","+results[1].label+","+nf(results[1].confidence, 0, 2);
      }
    }
    
    function incrementSeconds() {
      if(err){
          clearInterval(interval);
      }else if(seconds==uptime){
          document.getElementById("token").value=seconds;
          document.getElementById("myChallenge").submit();
      }else{
          seconds += 1;
      }
    }
    
    function addCountDown(){
        if(countdown>0  && !err){
         countdown--;
         setTimeout(addCountDown,1000);  
        }else{
         interval = setInterval(incrementSeconds, 1000);
         countdown=5;
        }
    }
</script>