// this code should connect real time data after database set up

const xV = [1, 2, 3, 4, 5, 6]; //should be time of day based on date
const yV = [2, 4, 6, 8, 10, 3]; //should be number of active users during that time of day
const myChart = new Chart("activeChart", {
    type: "line",
    data: {
        labels: xV,
        datasets: [{
            fill: false,
            backgroundColor:"rgba(0,0,255,1.0)",
            borderColor: "rgba(0,0,255,0.1)",
            data: yV
        }]
    },
    options: {}
  });

  const myChart1 = new Chart("reportChart", {
    type: "line",
    data: {
        labels: xV,
        datasets: [{
            fill: false,
            backgroundColor:"rgba(0,0,255,1.0)",
            borderColor: "rgba(0,0,255,0.1)",
            data: yV
        }]
    },
    options: {}
  });

document.querySelector(".search-bar input").addEventListener("input", function() {
    let query = this.value;
    if (query.length > 1) {
        fetch(`search.php?query=${query}`)
            .then(response => response.json())
            .then(data => {
                let results = "";
                data.forEach(user => {
                    results += `<p>${user.first_name} ${user.last_name} - ${user.email}</p>`;
                });
                document.querySelector(".search-results").innerHTML = results;
            });
    }
});


