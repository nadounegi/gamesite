
let navimg = document.querySelectorAll('#gnav img');
let navtext = document.querySelectorAll('#gnav a');

for (let i = 0; i < navimg.length; i++) {
  navimg[i].addEventListener('mouseover', function() {
    mOver(this);
  });
  navtext[i].addEventListener('mouseover', function() {
    mOver2(this);
  });
}

function mOver(obj) {
  obj.style.transition = '30s';
  obj.style.transform = 'translateY(20px)';
}

function mOver2(obj) {
  obj.style.opacity = '0';
}