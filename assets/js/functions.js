$(document).ready(function () {
    $( ".getAnswer" ).bind( "click", function() {
        getAnswer($(".question").val());
    });
});

function initData(data) {
    var answers,
        images,
        htmlContent = '',
        htmlNode;
    
    answers = data.answers;
    images = data.images;

    // run over images
    $.each(images, function(key, image) {
      htmlContent += '<li><img src="'+image.url+'"><span class="answerTxt"></span></li>';
    });

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
        'height' : 320,
        'width' : 620,
        'responsive' : true
    });
}

function getAnswer(question) {
    //var url = "http://api.flickr.com/services/feeds/photos_public.gne?tags=cat&tagmode=any&format=json&jsoncallback=?";
    var url = "http://qnsee.aws.af.cm/index.php/api"
    // var url = "http://localhost/index.php/api"
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