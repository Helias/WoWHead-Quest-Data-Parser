<?php
if ($_GET['quest'] == "" || !isset($_GET['quest']))
	$quest = "12969";
else
	$quest = $_GET['quest'];

$data = file_get_contents("http://www.wowhead.com/quest=" . $quest);
?>
<style type="text/css">
#content, #zul-bar { display: none; }
body {
	background-color: #fff !important;
	color: #000 !important;
}

#result { margin-left: 10px; }
</style>

<div id="content">
<?= $data ?>
</div>

<div id="result"></div>

<script>
window.onload = function() {
	var parsed = "<b>QuestID:</b> <?= $quest ?> <br>";

	var sidebar = document.getElementById("sidebar");
	var infobox = sidebar.getElementsByClassName("infobox");

	var li = infobox[0].getElementsByTagName("ul")[0].getElementsByTagName("li");

	var ProgressText = "", CompletionText =""; 
	var reqLevel, reqFaction = "none", reqClass = "none";

	if (document.getElementById("lknlksndgg-completion") != null) {
		CompletionText = document.getElementById("lknlksndgg-completion").innerHTML;
		
		CompletionText = CompletionText.replace(/\<br\>/g, "\n");
		CompletionText = CompletionText.replace(/\&nbsp\;/g, " ");
		CompletionText = CompletionText.replace(/\&lt\;/g, "<");
		CompletionText = CompletionText.replace(/\&gt\;/g, ">");
	}

	if (document.getElementById("lknlksndgg-progress") != null) {
		ProgressText = document.getElementById("lknlksndgg-progress").innerHTML;

		ProgressText = ProgressText.replace(/\<br\>/g, "\n");
		ProgressText = ProgressText.replace(/\&nbsp\;/g, " ");
		ProgressText = ProgressText .replace(/\&lt\;/g, "<");
		ProgressText = ProgressText .replace(/\&gt\;/g, ">");
	}

	
	for (var i = 0; i < li.length; i++) {
		if (li[i].innerHTML.indexOf("Requires level") > -1) {
			reqLevel = li[i].innerText.replace("Requires level ", "");
		}
		else if (li[i].innerHTML.indexOf("Side") > -1) {
			reqFaction = li[i].innerText.replace("Side: ", "");
		}
		else if (li[i].innerHTML.indexOf("Class") > -1) {
			reqClass = li[i].innerText.replace("Class:  ", "");
		}
	}

	parsed += "<b>Required Level:</b> " + reqLevel + "<br>";
	parsed += "<b>Faction: </b>" + reqFaction + "<br>";
	parsed += "<b>Class: </b>" + reqClass + "<br>";
	parsed += "<b>ProgressText: </b>" + ProgressText + "<br>";
	parsed += "<b>CompletionText: </b>" + CompletionText + "<br>";

	console.log("Required Level: " + reqLevel);
	console.log("Faction: " + reqFaction);
	console.log("Class: " + reqClass);
	console.log("ProgressText: " + ProgressText);
	console.log("CompletionText: " + CompletionText);

	var NextQuestID = "0", PrevQuestID = "0";
	var quests;

	if (infobox[1].getElementsByClassName("series")[0] != null) {
		quests = infobox[1].getElementsByClassName("series")[0].getElementsByTagName("td");

		for (var i = 0; i < quests.length; i++) {
			if (quests[i].innerHTML.indexOf("<b>") > -1 && quests[i].innerHTML.indexOf("<a") == -1) {
				if(quests[i-1] != null)
					PrevQuestID = quests[i-1].getElementsByTagName("a")[0].getAttribute("href").replace("/quest=", "");

				if(quests[i+1] != null)
					NextQuestID = quests[i+1].getElementsByTagName("a")[0].getAttribute("href").replace("/quest=", "");
			}
		}
	}

	console.log("Prev Quest ID: " + PrevQuestID);
	console.log("Next Quest ID:" + NextQuestID);
	
	parsed += "<b>Prev Quest ID: </b>" + PrevQuestID + "<br>";
	parsed += "<b>Next Quest ID: </b>" + NextQuestID + "<br>";
	
	document.getElementById("result").innerHTML = parsed;
};
</script>