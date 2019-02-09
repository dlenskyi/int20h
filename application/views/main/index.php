

<!-- MAIN (Center website) -->
<div class="main">

    <h1>INT20H</h1>
    <hr>

    <h2>Photo Emotion</h2>

    <div id="myBtnContainer">
        <button class="btn active" onclick="filterSelection('all')"> Show all</button>
        <button class="btn" onclick="filterSelection('sadness')"> Sadness</button>
        <button class="btn" onclick="filterSelection('neutral')"> Neutral</button>
        <button class="btn" onclick="filterSelection('disgust')"> Disgust</button>
        <button class="btn " onclick="filterSelection('anger')"> Anger</button>
        <button class="btn" onclick="filterSelection('surprise')"> Surprise</button>
        <button class="btn" onclick="filterSelection('fear')"> Fear</button>
        <button class="btn" onclick="filterSelection('happiness')"> Happiness</button>
        <button class="btn" onclick="filterSelection('none')"> None</button>
    </div>
    <div class="row">
        <?php foreach ($vars as $key => $value): ?>
        <div class="column <?php echo $value['emotion'];?>">
            <div class="content">
                <img src="<?php echo $value['img_url'];?>" style="width:100%">

            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>


