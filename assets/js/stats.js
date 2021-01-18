var d3 = require('d3');
//console.log(total_games);
jQuery(document).ready(function() {
    console.log(total_games);
    // Create dummy data
    var data = player_data;

    // set the dimensions and margins of the graph
    var width = 450;
    var height = 450;
    var margin = 40;



    // The radius of the pieplot is half the width or half the height (smallest one). I subtract a bit of margin.
    var radius = Math.min(width, height) / 2 - margin

    // append the svg object to the div called 'my_dataviz'
    var svg = d3.select("#placeholder")
    .append("svg")
    .attr("width", width)
    .attr("height", height)
    .append("g")
    .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

    
    // set the color scale
    var color = d3.scaleOrdinal()
    .domain(data)
    // red, green, blue, off-black, off-white
    //orange, teal, purple, yellow, pink
    //.range(["#ff3300", "#79f304", "#0936fb", "#303134", "#cfd1db"])
    //.range(["#f1a20a", "#0af1d4", "#c60af1", "#d4da10", "#f10d96"])
    .range(["#ff3300", "#79f304", "#0936fb", "#303134", "#cfd1db", "#f1a20a", "#0af1d4", "#c60af1", "#d4da10", "#f10d96"])

    // Compute the position of each group on the pie:
    var pie = d3.pie()
    .value(function(d) {console.log(d.value);return d.value;})
    var data_ready = pie(d3.entries(data))

    var arcGenerator = d3.arc()
    .innerRadius(0)
    .outerRadius(radius)

    // Build the pie chart: Basically, each part of the pie is a path that we build using the arc function.

    svg
    .selectAll('whatever')
    .data(data_ready)
    .enter()
    .append('path')
      .attr('d', arcGenerator)
      .attr('fill', function(d){ return(color(d.data.key)) })
      .attr("stroke", "black")
      .style("stroke-width", "2px")
      .style("opacity", 0.8)

    svg
    .selectAll('whatever')
    .data(data_ready)
    .enter()
    .append('text')
    .text(function(d){ return d.data.key + d.value + "%" })
    .attr("transform", function(d) { return "translate(" + arcGenerator.centroid(d) + ")";  })
    .style("text-anchor", "middle")
    .style("font-size", 17)


    
});