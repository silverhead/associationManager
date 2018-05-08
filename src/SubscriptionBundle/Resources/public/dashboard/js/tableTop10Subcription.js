new Morris.Donut({
    element: 'abo_chart',
    data: [{
        label: 'Premium 1 an',
        value: 10
    }, {
        label: 'Standard 1 an',
        value: 20
    }, {
        label: 'Standard 3 mois',
        value: 3
    }, {
        label: 'Standard 1 mois',
        value: 2
    }],
    colors: ['rgb(233, 30, 99)', 'rgb(0, 188, 212)', 'rgb(255, 152, 0)', 'rgb(0, 150, 136)'],
    formatter: function (y) {
        return y;// + '%'
    }
});