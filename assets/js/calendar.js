document.addEventListener('DOMContentLoaded', () => {

  const arrivalInput = document.getElementById('arrivalInput');
  const departureInput = document.getElementById('departureInput');

  // ARRIVAL
  document.querySelectorAll('.arrival-days li').forEach(day => {
    day.addEventListener('click', () => {
      const date = formatDate(day);
      arrivalInput.value = date;

      clearActive('.arrival-days');
      day.classList.add('active');

      fetchAvailability(date);
    });
  });

  // DEPARTURE
  document.querySelectorAll('.departure-days li').forEach(day => {
    day.addEventListener('click', () => {
      const date = formatDate(day);
      departureInput.value = date;

      clearActive('.departure-days');
      day.classList.add('active');
    });
  });

});

function formatDate(dayElement) {
  const day = dayElement.textContent.trim().padStart(2, '0');
  return `2026-01-${day}`;
}

function clearActive(selector) {
  document.querySelectorAll(`${selector} .active`)
    .forEach(el => el.classList.remove('active'));
}

function fetchAvailability(date) {
  fetch(`/assets/availability/availability.php?date=${date}`)
    .then(res => res.json())
    .then(data => {

      // update roomli
      document.querySelectorAll('#availability li').forEach(li => {
        const roomId = li.dataset.room;
        const span = li.querySelector('span');

        if (data[roomId]) {
          span.textContent = 'Available';
          span.className = 'available';
        } else {
          span.textContent = 'Booked';
          span.className = 'booked';
        }
      });

      // colored dates
      const dayLis = document.querySelectorAll('.arrival-days li');
      dayLis.forEach(li => li.classList.remove('available', 'booked'));

      const dayNumber = parseInt(date.split('-')[2], 10);
      const clickedDay = [...dayLis].find(li =>
        parseInt(li.textContent, 10) === dayNumber
      );

      if (clickedDay) {
        const allAvailable = Object.values(data).every(v => v === true);
        clickedDay.classList.add(allAvailable ? 'available' : 'booked');
      }

    })
    .catch(error => console.error('Availability error:', error));
}
