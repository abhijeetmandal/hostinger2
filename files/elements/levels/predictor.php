<?php
$usermodel = "../files/models/".$app->user->username.".json";
$model="";
if (file_exists($usermodel)) {
    $model="https://crushit.fit/files/models/".basename($usermodel);
    //$app->utils->message("Your trained model is being loaded.","good");
} else {
    $model = "";
    //$app->utils->message("Warning: Untrained model loaded. for better results train it <a href='/levels/plank/1/'>here.</a>");
}
//start of my code
//$target_dir = "../../files/uploads/models/";
$target_dir = "../files/models/";
//echo ". ".realpath(".")."<br/>";
//echo "target_dir ".realpath("../../files/uploads/models/")."<br/>";
$json_file = $target_dir . basename($_FILES["jsonUpload"]["name"]);
$bin_file = $target_dir . basename($_FILES["binUpload"]["name"]);
//echo "basename ".basename($_FILES["jsonUpload"]["name"])."<br/>";
    
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uploadOk = 1;
    $jsonFileType = strtolower(pathinfo($json_file,PATHINFO_EXTENSION));
    $binFileType = strtolower(pathinfo($bin_file,PATHINFO_EXTENSION));
    //echo "binFileType ".$binFileType."<br/>";
    // Check if image file is a actual image or fake image
    //if(isset($_POST["submit"])) {
    //    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    //    if($check !== false) {
    //        echo "File is an image - " . $check["mime"] . ".";
    //        $uploadOk = 1;
    //    } else {
    //        echo "File is not an image.";
    //        $uploadOk = 0;
    //    }
    //}
    // Check if file already exists
//    if (file_exists($json_file)) {
//        echo "Sorry, JSON file already exists. contact us to clean it";
//        $uploadOk = 0;
//    }
//    
//    if (file_exists($bin_file)) {
//        echo "Sorry, BINARY file already exists. contact us to clean it";
//        $uploadOk = 0;
//    }
    // Check file size
    if ($_FILES["jsonUpload"]["size"] > 500000) { //5000000 == 5MB
        //echo "Sorry, your JSON file is too large."+$_FILES["jsonUpload"]["size"];
        $app->utils->message("Sorry, your JSON file is too large."+$_FILES["jsonUpload"]["size"]);
        $uploadOk = 0;
    }
    
    // Check file size
    if ($_FILES["binUpload"]["size"] > 20000000) { //5000000 == 5MB
        //echo "Sorry, your BINARY file is too large."+$_FILES["binUpload"]["size"];
        $app->utils->message("Sorry, your BINARY file is too large."+$_FILES["binUpload"]["size"]);
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($jsonFileType != "json" || basename($_FILES["jsonUpload"]["name"]) != $app->user->username.".json") {
        //echo "Sorry found ".basename($_FILES["jsonUpload"]["name"]).", only ".$app->user->username.".json files is allowed.";
        $app->utils->message("Sorry, only ".$app->user->username.".json files is allowed.");
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($binFileType != "bin" || basename($_FILES["binUpload"]["name"]) != $app->user->username.".weights.bin") {
        //echo "Sorry found ".basename($_FILES["binUpload"]["name"]).", only ".$app->user->username.".weights.json files are allowed.";
        $app->utils->message("Sorry, only ".$app->user->username.".weights.json files are allowed.");
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        //echo "Sorry, your file was not uploaded.";
        $app->utils->message("Sorry, your file was not uploaded.");
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["jsonUpload"]["tmp_name"], $json_file)) {
            //Load the file
            //$contents = file_get_contents($json_file);

            //Decode the JSON data into a PHP array.
            //$contentsDecoded = json_decode($contents, true);

            //Modify the counter variable.
            //print_r($contentsDecoded['weightsManifest'][0]['paths'][0]);   
            //$contentsDecoded['weightsManifest'][0]['paths'][0]="https://crushit.fit/files/models/".basename($_FILES["binUpload"]["name"]);

            //Encode the array back into a JSON string.
            //$json = json_encode($contentsDecoded);

            //Save the file.
            //file_put_contents($json_file, $json);
            //echo "The file ". basename( $_FILES["jsonUpload"]["name"]). " has been uploaded.";
            $app->utils->message("The file ". basename( $_FILES["jsonUpload"]["name"]). " has been uploaded.","good");
        } else {
            //echo "Sorry, there was an error uploading your JSON file.";
            $app->utils->message("Sorry, there was an error uploading your JSON file.");
        }
        if (move_uploaded_file($_FILES["binUpload"]["tmp_name"], $bin_file)) {
            //echo "The file ". basename( $_FILES["binUpload"]["name"]). " has been uploaded.";
            $app->utils->message("The file ". basename( $_FILES["binUpload"]["name"]). " has been uploaded.","good");
            $app->utils->message("Congratulations , to continue the challenges <a href='/challenges/'>click here!!!</a>","good");
        } else {
            //echo "Sorry, there was an error uploading your BINARY file.";
            $app->utils->message("Sorry, there was an error uploading your BINARY file.");
        }
    }
}
//end of my code 
?>
<style>
button
{
 width: 100%;
 margin: 0 0 8px 0;
 padding: 2px;
 color: #fff;
 background: none;
 border: 1px solid #1e1e1e
}

input[type="file"]
{
 width: 100%;
 margin: 0 0 8px 0;
 padding: 2px;
 color: #fff;
 background: none;
 border: 1px solid #1e1e1e
}
</style>
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
    let countdown2=0;
    let err=false;
    let interval;
    let flagshow=true;
    let trainflag=false;
    let addimage;


    function modelReady() {
      console.log('Please wait, model is getting ready!!!');
      label='Please wait, model is getting ready!!!';
      classifier = mobilenet.classification(video, videoReady);
    }

     function customModelReady() {
       console.log('Custom Model is ready!!!');
       //label='Custom Model is getting ready!!!';
       //document.getElementById("category").value="<?= strtolower($challenge->group); ?>";
       //document.getElementById("challenge").value="<?= $challenge->name; ?>";
       //uptime="<?= $challenge->data['uptime']; ?>";
       //setTimeout(addCountDown,1000);
       //classifier.classify(gotResults);
     }

    function videoReady() {
      console.log('Video is ready!!!, Start Training the Model.');
      //adding dummy addImage here to give it a headstart
      //another bad fix for fixing delay
      model='<?= $model; ?>';
      if(model!=""){
        console.log('model: '+model);
        classifier.load(model, customModelReady);
      }else{
        console.log('adding bad plank ');
        addimage='bad plank';
        classifier.addImage(addimage);
      }
      label='Video is ready!!!, Start Training the Model.';
      //classifier.load('model6.json', customModelReady);
    }
    
    function saveReady() {
      console.log('Save the download 2 files if don\'t find 2 files, then please use Chrome');
      //adding dummy addImage here to give it a headstart
      //another bad fix for fixing delay
      label='Save the 2 files if don\'t find 2 files, then please use Chrome';
      //classifier.load('model6.json', customModelReady);
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
      goodSampleButton = createButton('Click here!!!');
      goodSampleButton.parent('goodDiv');
      goodSampleButton.mousePressed(function () {
        trainflag=false;
        label='get ready...';
        addimage='plank';
        console.log('addImage good sample!!!');
        setTimeout(addCountDown,1000);
      });

      badSampleButton = createButton('Click here!!!');
      badSampleButton.parent('badDiv');
      badSampleButton.mousePressed(function () {
        trainflag=false;
        label='get ready...';
        addimage='bad plank';
        console.log('addImage bad sample!!!');
        setTimeout(addCountDown,1000);
      });

      trainButton = createButton('Train the model');
      trainButton.parent('trainDiv');
      trainButton.mousePressed(function () {
        trainflag=true;
        label='training started...';
        console.log('training started...');
        classifier.train(whileTraining);
      });
      
      testButton = createButton('Verify before proceeding');
      testButton.parent('testDiv');
      testButton.mousePressed(function () {
        trainflag=true;
        label='testing...';
        console.log('testing..');
        classifier.classify(gotResults);
      });

      saveButton = createButton('save the model');
      saveButton.parent('saveDiv');
      saveButton.mousePressed(function () {
        trainflag=false;
        label='saving the 2 files...';
        console.log('saving the 2 files..');
        classifier.save(saveReady, '<?=$app->user->username;?>');
      });
      
//      filejson = createFileInput(jsonFile);
//      filejson.parent('jsonDiv');
//      filejson.mousePressed(function () {
//        trainflag=false;
//        label='Browse the *json file from download directory';
//        console.log('Browse the *json file from download directory');
//      });
      
//      binjson = createFileInput(binFile);
//      binjson.parent('binDiv');
//      binjson.mousePressed(function () {
//        trainflag=false;
//        label='Browse the *weights.bin file from download directory';
//        console.log('Browse the weights.bin file from download directory');
//      });
      
      
      
      function jsonFile(file) {
        print(file);
        if (file.type === 'application') {
          label="Found a APPLICATION file type";
        } else {
          label="Error: file type is not JSON";
        }
      }
      
      function binFile(file) {
        print(file);
        if (file.type === 'application') {
          label="Found a APPLICATION file type";
        } else {
          label="Error: file type is not BINARY";
        }
      }
      
      
    }

    function draw() {
      background(0);
      image(video, 0, 0, 480, 360);
      fill(255,0,0);
      textSize(16);
      //text(label, 10, height - 10);
      //dirty bug fix, todo global variables to be made local variables
      if(flagshow && countdown>=0){
        text("Click button, Starts in "+countdown, 300, 20);
      }
      //text(seconds +" sec", 10, 20);
      text(label, 10, 380);
    }


    function whileTraining(loss) {
      if (loss == null) {
        console.log('Training Complete');
        label='Training Complete';
        //classifier.classify(gotResults);
      } else {
        console.log(loss);
        label='Training loss: '+loss +' weights';
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
          if(trainflag){
            classifier.classify(gotResults);
          }  
        //console.log(results);
        //label = results[0].label+","+nf(results[0].confidence, 0, 2)+","+results[1].label+","+nf(results[1].confidence, 0, 2);
      }
    }
    
    function incrementSeconds() {
      if(seconds==30){
          clearInterval(interval);
      }else{
          seconds += 1;
          label = "added sample "+seconds;
          classifier.addImage('plank');
      }
    }
    
    function incrementMilliSeconds(){
        if(countdown2>30){
           //abort
           label = "Done, "+countdown2+" sample added";
           countdown2=0;
           flagshow=true;
        }else{
         //continue
         countdown2++;
         label = countdown2+" sample added:"+addimage;
         //adding1
         classifier.addImage(addimage);
         //adding2
         //classifier.addImage('plank');
         //adding3
         //classifier.addImage('plank');
         setTimeout(incrementMilliSeconds,500);
        }
    }
    
    function addCountDown(){
        if(countdown>0){
         countdown--;
         setTimeout(addCountDown,1000);  
        }else{
         //interval = setInterval(incrementSeconds, 500);
         setTimeout(incrementMilliSeconds,500);
         flagshow=false;
         countdown=5;
        }
    }
</script>


  <h3>Train the Machine Learning Model:</h3>
  <h4>(Note: for optimal experience use <b>chrome browser</b>)</h4> 
<table>
  <tr>
    <td>Step#1:</td>
    <td>to add some good training sample:<div id='goodDiv'></div></td>
  </tr>
  <tr>
    <td>Step#2:</td>
    <td>to add some bad training sample:<div id='badDiv'></div></td>
  </tr>
  <tr>
    <td>Step#3</td>
    <td><div id='trainDiv'></div></td>
  </tr>
  <tr>
    <td>Step#4</td>
    <td><div id='testDiv'></div></td>
  </tr>
  <tr>
    <td>Step#5</td>
    <td><div id='saveDiv'></div></td>
  </tr>
<form method="post" enctype="multipart/form-data">  
  <tr>
    <td>Step#6:</td>
    <td>Choose file: *.json</td>
    <td><input type="file" name="jsonUpload" id="jsonUpload"></td>
  </tr>
  <tr>
    <td>Step#7:</td>
    <td>Choose file: *.weights.bin</td>
    <td><input type="file" name="binUpload" id="binUpload"></td>
  </tr>
  <tr>
    <td>Step#8:</td>
    <td>upload the training:<input type="hidden" name="category" value="plank">
    <input type="hidden" name="level" value="1"><input type="submit" value="Submit"></td>
  </tr>
</form>  
</table>    