$(function() {
    "use strict";

    // chart 1
    var ctx1 = document.getElementById("chart1").getContext('2d');
   
    var gradientStroke1 = ctx1.createLinearGradient(0, 0, 0, 300);
    gradientStroke1.addColorStop(0, '#6078ea');  
    gradientStroke1.addColorStop(1, '#17c5ea'); 
   
    var gradientStroke2 = ctx1.createLinearGradient(0, 0, 0, 300);
    gradientStroke2.addColorStop(0, '#ff8359');
    gradientStroke2.addColorStop(1, '#ffdf40');

    var myChart1 = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Laptops',
                data: [65, 59, 80, 81, 65, 59, 80, 81, 59, 80, 81, 65],
                borderColor: '#fff',
                backgroundColor: gradientStroke1,
                hoverBackgroundColor: gradientStroke1,
                fill: false,
                borderRadius: 20,
                borderWidth: 1
            }, {
                label: 'Mobiles',
                data: [28, 48, 40, 19, 28, 48, 40, 19, 40, 19, 28, 48],
                borderColor: '#fff',
                backgroundColor: gradientStroke2,
                hoverBackgroundColor: gradientStroke2,
                fill: false,
                borderRadius: 20,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            barPercentage: 0.5,
            categoryPercentage: 0.8,
            animation: {
                duration: 1000,
                easing: 'easeInOutQuad',
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#333',
                        font: { size: 12 }
                    }
                },
                tooltip: {
                    enabled: true,
                    backgroundColor: 'rgba(0,0,0,0.7)',
                    titleFont: { size: 14 },
                    bodyFont: { size: 12 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // chart 2
    var ctx2 = document.getElementById("chart2").getContext('2d');

    var gradientStroke1_2 = ctx2.createLinearGradient(0, 0, 0, 300);
    gradientStroke1_2.addColorStop(0, '#fc4a1a');
    gradientStroke1_2.addColorStop(1, '#f7b733');

    var gradientStroke2_2 = ctx2.createLinearGradient(0, 0, 0, 300);
    gradientStroke2_2.addColorStop(0, '#4776e6');
    gradientStroke2_2.addColorStop(1, '#8e54e9');

    var gradientStroke3_2 = ctx2.createLinearGradient(0, 0, 0, 300);
    gradientStroke3_2.addColorStop(0, '#ee0979');
    gradientStroke3_2.addColorStop(1, '#ff6a00');
    
    var gradientStroke4_2 = ctx2.createLinearGradient(0, 0, 0, 300);
    gradientStroke4_2.addColorStop(0, '#42e695');
    gradientStroke4_2.addColorStop(1, '#3bb2b8');

    var myChart2 = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ["Jeans", "T-Shirts", "Shoes", "Lingerie"],
            datasets: [{
                backgroundColor: [
                    gradientStroke1_2,
                    gradientStroke2_2,
                    gradientStroke3_2,
                    gradientStroke4_2
                ],
                hoverBackgroundColor: [
                    gradientStroke1_2,
                    gradientStroke2_2,
                    gradientStroke3_2,
                    gradientStroke4_2
                ],
                data: [25, 80, 25, 25],
                borderWidth: [1, 1, 1, 1]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: 82,
            animation: {
                duration: 1000,
                easing: 'easeInOutQuad',
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#333',
                        font: { size: 12 }
                    }
                },
                tooltip: {
                    enabled: true,
                    backgroundColor: 'rgba(0,0,0,0.7)',
                    titleFont: { size: 14 },
                    bodyFont: { size: 12 }
                }
            }
        }
    });

    // world map
    jQuery('#geographic-map-2').vectorMap({
        map: 'world_mill_en',
        backgroundColor: 'transparent',
        borderColor: '#818181',
        borderOpacity: 0.25,
        borderWidth: 1,
        zoomOnScroll: false,
        color: '#009efb',
        regionStyle: {
            initial: {
                fill: '#008cff'
            }
        },
        markerStyle: {
            initial: {
                r: 9,
                'fill': '#fff',
                'fill-opacity': 1,
                'stroke': '#000',
                'stroke-width': 5,
                'stroke-opacity': 0.4
            },
        },
        enableZoom: true,
        hoverColor: '#009efb',
        markers: [{
            latLng: [21.00, 78.00],
            name: 'Lorem Ipsum Dollar'
        }],
        hoverOpacity: null,
        normalizeFunction: 'linear',
        scaleColors: ['#b6d6ff', '#005ace'],
        selectedColor: '#c9dfaf',
        selectedRegions: [],
        showTooltip: true,
    });

    // chart 3
    var ctx3 = document.getElementById('chart3').getContext('2d');

    var gradientStroke1_3 = ctx3.createLinearGradient(0, 0, 0, 300);
    gradientStroke1_3.addColorStop(0, '#00b09b');
    gradientStroke1_3.addColorStop(1, '#96c93d');

    var myChart3 = new Chart(ctx3, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Facebook',
                data: [5, 30, 16, 23, 8, 14, 2],
                backgroundColor: gradientStroke1_3,
                fill: {
                    target: 'origin',
                    above: 'rgb(21 202 32 / 15%)'
                }, 
                tension: 0.4,
                borderColor: gradientStroke1_3,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 1000,
                easing: 'easeInOutQuad',
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#333',
                        font: { size: 12 }
                    }
                },
                tooltip: {
                    enabled: true,
                    backgroundColor: 'rgba(0,0,0,0.7)',
                    titleFont: { size: 14 },
                    bodyFont: { size: 12 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // chart 4
    var ctx4 = document.getElementById("chart4").getContext('2d');

    var gradientStroke1_4 = ctx4.createLinearGradient(0, 0, 0, 300);
    gradientStroke1_4.addColorStop(0, '#ee0979');
    gradientStroke1_4.addColorStop(1, '#ff6a00');
    
    var gradientStroke2_4 = ctx4.createLinearGradient(0, 0, 0, 300);
    gradientStroke2_4.addColorStop(0, '#283c86');
    gradientStroke2_4.addColorStop(1, '#39bd3c');

    var gradientStroke3_4 = ctx4.createLinearGradient(0, 0, 0, 300);
    gradientStroke3_4.addColorStop(0, '#7f00ff');
    gradientStroke3_4.addColorStop(1, '#e100ff');

    var myChart4 = new Chart(ctx4, {
        type: 'pie',
        data: {
            labels: ["Completed", "Pending", "Process"],
            datasets: [{
                backgroundColor: [
                    gradientStroke1_4,
                    gradientStroke2_4,
                    gradientStroke3_4
                ],
                hoverBackgroundColor: [
                    gradientStroke1_4,
                    gradientStroke2_4,
                    gradientStroke3_4
                ],
                data: [50, 50, 50],
                borderWidth: [1, 1, 1]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: 0, // Pie chart has no cutout
            animation: {
                duration: 1000,
                easing: 'easeInOutQuad',
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#333',
                        font: { size: 12 }
                    }
                },
                tooltip: {
                    enabled: true,
                    backgroundColor: 'rgba(0,0,0,0.7)',
                    titleFont: { size: 14 },
                    bodyFont: { size: 12 }
                }
            }
        }
    });

    // chart 5
    var ctx5 = document.getElementById("chart5").getContext('2d');
   
    var gradientStroke1_5 = ctx5.createLinearGradient(0, 0, 0, 300);
    gradientStroke1_5.addColorStop(0, '#f54ea2');
    gradientStroke1_5.addColorStop(1, '#ff7676');

    var gradientStroke2_5 = ctx5.createLinearGradient(0, 0, 0, 300);
    gradientStroke2_5.addColorStop(0, '#42e695');
    gradientStroke2_5.addColorStop(1, '#3bb2b8');

    var myChart5 = new Chart(ctx5, {
        type: 'bar',
        data: {
            labels: [1, 2, 3, 4, 5],
            datasets: [{
                label: 'Clothing',
                data: [40, 30, 60, 35, 60],
                borderColor: gradientStroke1_5,
                backgroundColor: gradientStroke1_5,
                hoverBackgroundColor: gradientStroke1_5,
                fill: false,
                borderWidth: 1
            }, {
                label: 'Electronic',
                data: [50, 60, 40, 70, 35],
                borderColor: gradientStroke2_5,
                backgroundColor: gradientStroke2_5,
                hoverBackgroundColor: gradientStroke2_5,
                fill: false,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            barPercentage: 0.5,
            categoryPercentage: 0.8,
            animation: {
                duration: 1000,
                easing: 'easeInOutQuad',
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#333',
                        font: { size: 12 }
                    }
                },
                tooltip: {
                    enabled: true,
                    backgroundColor: 'rgba(0,0,0,0.7)',
                    titleFont: { size: 14 },
                    bodyFont: { size: 12 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

});
