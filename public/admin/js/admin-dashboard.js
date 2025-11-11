$("#updateProgressForm").parsley();
// Get context with jQuery - using jQuery's .get() method.
var enquiryChartCanvas = $('#enquiryChart').get(0).getContext('2d');
// This will get the first returned node in the jQuery collection.
var enquiryChart       = new Chart(enquiryChartCanvas);

var enquiryChartData = {
  labels  : months,
  datasets: [
    {
      label               : 'Enquires in progress',
      fillColor           : 'rgba(104,205,201,0.5)',
      barThickness: 1,
      barPercentage: 0.5,
      maxBarThickness: 1,
      data                : enquiriesInProgress
    },
    {
      label               : 'Closed enquiries',
      fillColor           : 'rgba(0,204,102,0.8)',
      barThickness: 1,
      barPercentage: 0.5,
      maxBarThickness: 1,
      data                : closedEnquiries
    },
    {
        label               : 'Open enquiries',
        fillColor           : 'rgba(145,38,128,0.5)',
        barThickness: 1,
        barPercentage: 0.5,
        maxBarThickness: 1,
        data                : openEnquiries
    }
  ]
};

var enquiryChartOptions = {
 // Boolean - If we should show the scale at all
 showScale               : true,
 // Boolean - Whether grid lines are shown across the chart
 scaleShowGridLines      : false,
  // String - A legend template
  legendTemplate          : '<ul class=\'<%=name.toLowerCase()%>-legend\'><% for (var i=0; i<datasets.length; i++){%><li><span style=\'background-color:<%=datasets[i].lineColor%>\'></span><%=datasets[i].label%></li><%}%></ul>',
  // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
  maintainAspectRatio     : true,
  // Boolean - whether to make the chart responsive to window resizing
  responsive              : true,
  
};

$(document).ready(function(){
  // Create the line chart
  enquiryChart.Bar(enquiryChartData,enquiryChartOptions);
});


$(".year-dropdown").change(function(){
    var year = $(this).val();
    url = getYearEnuiryUrl.replace(':year', year);
    $.ajax({
      url:url,
      success:function(data){
        data = JSON.parse(data);
        console.log(enquiryChart);
      
          enquiryChartData.datasets[0].data =  data['In progress'];
          enquiryChartData.datasets[1].data =  data['Closed'];
          enquiryChartData.datasets[2].data =  data['Open'];
          enquiryChartData.labels =  data.months;
          // enquiryChartCanvas = $('#enquiryChart').get(0).getContext('2d');
          // enquiryChart = new Chart(enquiryChartCanvas);
          console.log(enquiryChartData);
          enquiryChart.Bar(enquiryChartData,enquiryChartOptions);
      
        $("#chartYear").text(year);
      }
  });
});


  // -------------
  // - PIE CHART -
  // -------------
  // Get context with jQuery - using jQuery's .get() method.
  var pieChartCanvas = $('#enquiryPieChart').get(0).getContext('2d');
  var pieChart       = new Chart(pieChartCanvas);
  var PieData        = [
    {
      value    : openEnquiryCM,
      color    : 'rgba(145,38,128,0.5)',
      highlight: 'rgba(145,38,128,0.5)',
      label    : 'Open enquires'
    },
    {
      value    : closedEnquiryCM,
      color    : 'rgba(0,204,102,0.5)',
      highlight: 'rgba(0,204,102,0.5)',
      label    : 'Closed enquiries'
    },
    {
      value    : enquiryInProgressCM,
      color    : 'rgba(104,205,201,0.8)',
      highlight: 'rgba(104,205,201,0.8)',
      label    : 'Enquiries in progress'
    },
    
  ];
  var pieOptions     = {
    // Boolean - Whether we should show a stroke on each segment
    segmentShowStroke    : true,
    // String - The colour of each segment stroke
    segmentStrokeColor   : '#fff',
    // Number - The width of each segment stroke
    segmentStrokeWidth   : 1,
    // Number - The percentage of the chart that we cut out of the middle
    percentageInnerCutout: 50, // This is 0 for Pie charts
    // Number - Amount of animation steps
    animationSteps       : 100,
    // String - Animation easing effect
    animationEasing      : 'easeOutBounce',
    // Boolean - Whether we animate the rotation of the Doughnut
    animateRotate        : true,
    // Boolean - Whether we animate scaling the Doughnut from the centre
    animateScale         : false,
    // Boolean - whether to make the chart responsive to window resizing
    responsive           : true,
    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio  : false,
    // String - A legend template
    legendTemplate       : '<ul class=\'<%=name.toLowerCase()%>-legend\'><% for (var i=0; i<segments.length; i++){%><li><span style=\'background-color:<%=segments[i].fillColor%>\'></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>',
    // String - A tooltip template
    tooltipTemplate      : '<%=value %> <%=label%>'
  };
  // Create pie or douhnut chart
  // You can switch between pie and douhnut using the method below.
  pieChart.Doughnut(PieData, pieOptions);
  // -----------------
  // - END PIE CHART -
  // -----------------

