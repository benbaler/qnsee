$(document).ready(function () {
    $( ".getAnswer" ).bind( "click", function() {
        getAnswer($(".question").val());
    });
});

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

    // Operate the slideshow via bjqs plugin (http://basic-slider.com/)
    $('#answerContainer').bjqs({
        'height' : $(window).height(),
        'width' : $(window).width(),
        'responsive' : true
    });
}

function getAnswer(question) {
    var url = "http://qnsee.aws.af.cm/index.php/api"
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