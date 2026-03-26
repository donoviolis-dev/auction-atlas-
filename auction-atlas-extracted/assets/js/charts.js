/**
 * Auction Atlas - Chart.js Utilities
 * 
 * Reusable chart creation functions for radar charts,
 * bar charts, and doughnut charts used across the platform.
 */

/**
 * Create a radar chart for auction profile comparison
 * 
 * @param {string} canvasId - Canvas element ID
 * @param {object} data - Chart data with labels and datasets
 * @returns {Chart} Chart.js instance
 */
function createRadarChart(canvasId, data) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return null;
    
    return new Chart(ctx, {
        type: 'radar',
        data: {
            labels: data.labels || ['Trust', 'Compliance', 'Reputation', 'Operations', 'Fee Transparency'],
            datasets: data.datasets.map(function(ds, i) {
                const colors = [
                    { bg: 'rgba(31, 78, 121, 0.2)', border: '#1F4E79' },
                    { bg: 'rgba(42, 157, 143, 0.2)', border: '#2A9D8F' },
                    { bg: 'rgba(255, 215, 0, 0.2)', border: '#FFD700' },
                    { bg: 'rgba(244, 162, 97, 0.2)', border: '#F4A261' },
                ];
                const color = colors[i % colors.length];
                
                return {
                    label: ds.label,
                    data: ds.data,
                    backgroundColor: ds.backgroundColor || color.bg,
                    borderColor: ds.borderColor || color.border,
                    borderWidth: 2,
                    pointBackgroundColor: ds.borderColor || color.border,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                };
            })
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { family: 'Lato', size: 12 },
                        padding: 15,
                        usePointStyle: true,
                    }
                }
            },
            scales: {
                r: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        stepSize: 20,
                        font: { family: 'Lato', size: 10 },
                        backdropColor: 'transparent',
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.05)',
                    },
                    pointLabels: {
                        font: { family: 'Lato', size: 11, weight: '600' },
                        color: '#475569',
                    }
                }
            }
        }
    });
}

/**
 * Create a horizontal bar chart for risk breakdown
 * 
 * @param {string} canvasId - Canvas element ID
 * @param {object} data - Chart data
 * @returns {Chart} Chart.js instance
 */
function createRiskBarChart(canvasId, data) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return null;
    
    return new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels || ['Operational', 'Compliance', 'Fee', 'Market'],
            datasets: [{
                label: data.label || 'Risk Score',
                data: data.values,
                backgroundColor: data.values.map(function(v) {
                    if (v < 25) return 'rgba(16, 185, 129, 0.7)';
                    if (v < 50) return 'rgba(245, 158, 11, 0.7)';
                    if (v < 75) return 'rgba(244, 162, 97, 0.7)';
                    return 'rgba(239, 68, 68, 0.7)';
                }),
                borderColor: data.values.map(function(v) {
                    if (v < 25) return '#10B981';
                    if (v < 50) return '#F59E0B';
                    if (v < 75) return '#F4A261';
                    return '#EF4444';
                }),
                borderWidth: 1,
                borderRadius: 6,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.x + '/100';
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    max: 100,
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: { font: { family: 'Lato', size: 11 } }
                },
                y: {
                    grid: { display: false },
                    ticks: { font: { family: 'Lato', size: 12, weight: '600' } }
                }
            }
        }
    });
}

/**
 * Create a doughnut chart for grade distribution
 * 
 * @param {string} canvasId - Canvas element ID
 * @param {object} data - Chart data with grade counts
 * @returns {Chart} Chart.js instance
 */
function createGradeChart(canvasId, data) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return null;
    
    return new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Grade A', 'Grade B', 'Grade C'],
            datasets: [{
                data: [data.A || 0, data.B || 0, data.C || 0],
                backgroundColor: [
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                ],
                borderColor: ['#10B981', '#F59E0B', '#EF4444'],
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { family: 'Lato', size: 12 },
                        padding: 15,
                        usePointStyle: true,
                    }
                }
            }
        }
    });
}

/**
 * Create a bar chart for national averages
 * 
 * @param {string} canvasId - Canvas element ID
 * @param {object} data - National average data
 * @returns {Chart} Chart.js instance
 */
function createNationalBarChart(canvasId, data) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return null;
    
    return new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'National Average',
                data: data.values,
                backgroundColor: 'rgba(31, 78, 121, 0.7)',
                borderColor: '#1F4E79',
                borderWidth: 1,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: { font: { family: 'Lato', size: 11 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Lato', size: 11, weight: '600' } }
                }
            }
        }
    });
}
