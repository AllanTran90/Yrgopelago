console.log("CALENDAR.JS VERSION: 2026-01-08-19:XX");
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

      if (arrivalInput.value) {
        fetchAvailability(arrivalInput.value, date);
      
    }
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

      if (location.search.includes('refresh')) {
    const arrivalValue = arrivalInput.value;

    if (arrivalValue) {
      fetchAvailability(arrivalValue);
    }
  }

});

function formatDate(dayElement) {
  const day = Number(dayElement.innerText);
  return `2026-01-${String(day).padStart(2, '0')}`;
}

function clearActive(selector) {
  document.querySelectorAll(`${selector} .active`)
    .forEach(el => el.classList.remove('active'));
}
function fetchAvailability(arrivalDate, departureDate = null) {
  console.log('fetchAvailability CALLED with', arrivalDate, departureDate);

  let url = `assets/availability/availability-api.php?arrival=${arrivalDate}`;
    if (departureDate) {
    url += `&departure=${departureDate}`;
  }

  fetch(url)
    .then(res => res.json())
    .then(data => {
      console.log('AVAILABILITY DATA:', data);

      if (!data || Object.keys(data).length === 0){
        return;
      }

      // update roomli
      document.querySelectorAll('#availability li').forEach(li => {
        const roomId = li.dataset.room;
        const span = li.querySelector('.status');



          span.classList.remove('available', 'booked');
          span.className.add(data[roomId] ? 'available' : 'booked');
          span.textContent = data[roomId] ?  'AVAILABLE!!!' : 'BOOKED!!!';
      
          });

      // colored dates
      const dayLis = document.querySelectorAll('.arrival-days li');
      dayLis.forEach(li => li.classList.remove('available', 'booked'));

      const dayNumber = parseInt(arrivalDate.split('-')[2], 10);
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
