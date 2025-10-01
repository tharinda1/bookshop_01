<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cal-Heatmap Chart</title>
    
    <script src="https://d3js.org/d3.v7.min.js"></script>
    
    <script src="https://unpkg.com/cal-heatmap/dist/cal-heatmap.min.js"></script>
    
    <link rel="stylesheet" href="https://unpkg.com/cal-heatmap/dist/cal-heatmap.css">
    
</head>
<body>
    <h1>My Cal-Heatmap Chart</h1>
    <div id="cal-heatmap"></div>

<!--
    <script>
        const cal = new CalHeatmap();
        cal.paint({
            itemSelector: '#cal-heatmap',
            domain: {
                type: 'month',
                label: { text: 'MMM' }
            },
            subDomain: {
                type: 'day',
                radius: 2,
                width: 11,
                height: 11
            },
            range: 12,
            date: {
                start: new Date('2025-07-01')
            },
            data: {
                source: {
                    '1719881600000': 3,
                    '1720313600000': 1,
                    '1720918400000': 4,
                },
                type: 'json'
            }
        });
    </script> -->
    <script>
        const cal = new CalHeatmap();

        // v4 expects an array of records, not an object map
        const data = [
            { date: new Date('2024-01-01').getTime(), value: 5 },
            { date: new Date('2024-01-02').getTime(), value: 10 },
            { date: new Date('2024-01-05').getTime(), value: 2 },
            { date: new Date('2024-01-10').getTime(), value: 7 },
            { date: new Date('2024-02-01').getTime(), value: 15 },
            { date: new Date('2024-02-03').getTime(), value: 8 },
            { date: new Date('2024-02-15').getTime(), value: 3 },
        ];

        cal.paint({
            itemSelector: '#cal-heatmap',
            date: { start: new Date('2024-01-01') },
            range: 3,
            domain: { type: 'month' },
            subDomain: { type: 'day' },
            data: {
            source: data,
            x: 'date',     // how to read the date
            y: 'value',    // how to read the value
            // defaultValue: 0, // uncomment if you want empty days colored as 0
            },
            scale: {
            color: {
                type: 'linear',
                range: ['#ebedf0', '#9be9a8', '#40c463', '#30a14e', '#216e39'],
                domain: [0, 15],
            },
            },
        });
    </script>
</body>
</html>