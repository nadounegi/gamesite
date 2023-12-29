let items = document.querySelectorAll('.content');
items.forEach(item => {
  item.addEventListener('mousemove', (e) => {
    let positionPx = e.x - item.getBoundingClientRect().left;
    let positionX = (positionPx / item.offsetWidth) * 100;
    let positionPy = e.y - item.getBoundingClientRect().top;
    let positionY = (positionPy / item.offsetHeight) * 100;

    item.style.setProperty('--rx', (0.5 * (50 - positionY)) + 'deg');
    item.style.setProperty('--ry', -(0.5 * (50 - positionX)) + 'deg');
  });

  item.addEventListener('mouseout', () => {
    item.style.setProperty('--rx', '0deg');
    item.style.setProperty('--ry', '0deg');
  });
});
