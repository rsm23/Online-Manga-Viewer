      $(".collapse").collapse();
      $('.dropdown-toggle').dropdown();
      $('.img img').mouseover(function(){
            $('body').css("background" , "#000");
            $('.panel').css("border", "1px solid #000");     	
            $('.img img').css("zoom", "135%");
            $('.img').css("background", "#000");
      });
      $('.img img').mouseout(function(){
            $('body').css("background", "#fff");
            $('.panel').css("border", "1px solid #dddddd");
            $('.img img').css("zoom", "100%");
            $('.img').css("background", "#fff");
      });