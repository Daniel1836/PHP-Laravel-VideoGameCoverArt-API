 <html>
 
 <body>
     
 @foreach($popularGames as $game) 
         <img src="{{ $game['coverImageUrl'] }}">
 @endforeach
    
 </body>
</html>
