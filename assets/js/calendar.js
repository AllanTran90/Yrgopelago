document.addEventListener('DOMContentLoaded', () => {

  const arrivalInput = document.getElementById('arrivalInput');
  const departureInput = document.getElementById('departureInput');

  // ARRIVAL
  document.querySelectorAll('.arrival-days li').forEach(day => {
    day.addEventListener('click', () => {
       console.log('DAY CLICKED', day.textContent);

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

  const roomPrices = {
    1: 5,
    2: 7,
    3: 10
  };

  const featurePrices = {
    scuba: 5,
    pingpong: 5,
    bicykle: 5,
    casino: 17
  };

  function updateTotal() {
    let total = 0;

    // selected room
    const selectedRoom = document.getElementById('roomInput').value;
    if (selectedRoom && roomPrices[selectedRoom]) {
      total += roomPrices[selectedRoom];
    }

    // selected features
    document
      .querySelectorAll('input[name="features[]"]:checked')
      .forEach(feature => {
        total += featurePrices[feature.value] || 0;
      });

    const totalEl = document.getElementById('totalPrice');
    if (totalEl) {
      totalEl.innerHTML = `<strong>Total amount: ${total} kr</strong>`;
    }
  }

  // the choosen features
  document
    .querySelectorAll('input[name="features[]"]')
    .forEach(input => {
      input.addEventListener('change', updateTotal);
    });

  // update when a room is choosen
  document
    .querySelectorAll('#availability li')
    .forEach(li => {
      li.addEventListener('click', updateTotal);
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
 fetch(`/assets/availability/availability-api.php?date=` + date)
    .then(res => res.json())
    .then(data => {

      if (!data || Object.keys(data).length === 0){
        return;
      }

      // update roomli
      document.querySelectorAll('#availability li').forEach(li => {
        const roomId = li.dataset.room;
        const span = li.querySelector('span');

        span.classList.remove('available', 'booked');

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

document.querySelectorAll('#availability li').forEach(li => {
  li.addEventListener('click', () => {
    const roomId = li.dataset.room;
    const status = li.querySelector('span');

    if (status.classList.contains('booked')) {
      alert('This room is already booked');
      return;
    }

    document.querySelectorAll('#availability li')
      .forEach(el => el.classList.remove('active'));

    li.classList.add('active');


    document.getElementById('roomInput').value = roomId;
  });
});
