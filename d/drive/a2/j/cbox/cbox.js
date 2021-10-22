window.addEventListener('load', onLoadA2Cb, false);

function onClickA2Cb(evt){
  var t = evt.currentTarget,
         img = ee(t, 'img')[0],
         inp = ee(t, 'input')[0],
         gb = 'green-border';
  if(inp.value == 1){
      inp.value = 0;
      attr(img, 'src', root + '/i/check_intive.jpg');
      removeClass(img, gb);
  } else {
      attr(img, 'src', root +  '/i/check_active.jpg');
      inp.value = 1;
      addClass(img, gb);
  }
}

function onLoadA2Cb(){
     var ls = cs(document, 'cbWrapper'), i;
     for(i = 0; i < sz(ls); i++){
       ls[i].onclick = onClickA2Cb;
     }
}
