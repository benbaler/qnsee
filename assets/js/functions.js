$(document).ready(function () {
    // Detect whether device supports orientationchange event, otherwise fall back to
    // the resize event.
    /*var supportsOrientationChange = "onorientationchange" in window,
        orientationEvent = supportsOrientationChange ? "orientationchange" : "resize";

    window.addEventListener(orientationEvent, function() {
        setSlide();
    }, false);*/
    $(".getAnswer").bind( "click", function() {
        getAnswer($(".question").val());
    });
    $(document).keypress(function(e) {
        if((e.which == 13) && $("#questionContainer").is(":visible")) {
            getAnswer($(".question").val());
        }
    });
    $("span.btnHome").bind( "click", function() {
        backHome();
    });
});
function backHome() {
    $("ul.bjqs").html("");
    $("#questionContainer").show();
    $("#answerContainer").hide();
}
function setSlide() {
    // Operate the slideshow via bjqs plugin (http://basic-slider.com/)
    $('#answerContainer').bjqs({
        'height' : $(window).height(),
        'width' : $(window).width(),
        'responsive' : true
    });
}
function initData(data) {
    var answers,
        images,
        htmlContent = '',
        htmlNode,
        imageIndex;
    
    answers = data.answers;
    images = data.images;


    for (var answerIndex = 0; answerIndex < answers.length; answerIndex++) {
        imageIndex = answerIndex % images.length;
        console.log("ans : "+answerIndex+" | ima : "+imageIndex);
        htmlContent += '<li><img src="'+images[imageIndex].url+'"><span class="answerTxt">'+answers[answerIndex].text+'</span></li>';
    }

    /*
    for (var i = 0; i < data.items.length; i++) {
        answers = data.items[i];
        answersTxt = answer.tags;
        answersPic = answer.media.m;
        htmlContent += '<li><img src="'+answerPic+'"><span class="answerTxt">'+answerTxt+'</span></li>';
    }
    */
    $("#questionContainer").hide();
    $("#answerContainer").show();
    htmlNode = $(".bjqs");
    htmlNode.append(htmlContent);
    setSlide();
}
function getAnswer(question) {
    if((question != "") && (question != null)) {
        var url = "http://qnsee.aws.af.cm/index.php/api"
        //var url = "http://localhost/index.php/api"
        //var url = "test.json"
        $.ajax({
          url: url,
          cache: false,
          data: {q:question},
          dataType: "json",
          success: function(data){
            initData(data);
          },
          error: function(e, xhr){
            alert("error!");
          }
        });
    }
}