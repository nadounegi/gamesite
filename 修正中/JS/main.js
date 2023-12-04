// 获取导航栏的所有链接
const navLinks = document.querySelectorAll('#gnav li a');

// 遍历每个链接
navLinks.forEach(link => {
  // 添加鼠标悬停事件监听器
  link.addEventListener('mouseover', () => {
    // 获取链接后面的图片和文字元素
    const siblingContainer = link.nextElementSibling;
    if (siblingContainer) {
      const image = siblingContainer.querySelector('.game-image img');
      const info = siblingContainer.querySelector('.game-info');

      // 图片下移
      if (image) {
        image.style.transform = 'translateY(20px)';
      }
      
      // 文字消失
      if (info) {
        info.style.display = 'none';
      }
    }
  });

  // 添加鼠标离开事件监听器
  link.addEventListener('mouseout', () => {
    // 获取链接后面的图片和文字元素
    const siblingContainer = link.nextElementSibling;
    if (siblingContainer) {
      const image = siblingContainer.querySelector('.game-image img');
      const info = siblingContainer.querySelector('.game-info');

      // 图片恢复原位
      if (image) {
        image.style.transform = 'translateY(0)';
      }
      
      // 文字显示
      if (info) {
        info.style.display = 'block';
      }
    }
  });
});