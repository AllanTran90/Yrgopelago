const bookedDays = [, ,];
document.querySelectorAll('.days li'). forEach(day => {
    const dayNumber = parseInt(days.textContent);

    if (bookedDays.includes(dayNumber)){
        day.classList.add('booked');
    }
    else{
        day.classList.add('available');
    }
    
    day.addEventListener('click', () =>{
        if(day.classList.contains('booked')) return;

        document.querySelectorAll('.days.li'). forEach (d => d.classList.remove('active'));
        day.classList.add('active');
    });
});