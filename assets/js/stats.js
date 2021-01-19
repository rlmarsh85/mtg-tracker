var d3 = require('d3');
var d3legend = require('d3-svg-legend');

//console.log(total_games);
jQuery(document).ready(function() {

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
    .attr("width", width + 200)
    .attr("height", height)
    .append("g")
    .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

    // red, green, blue, off-black, off-white
    //orange, teal, purple, yellow, pink    
    //var colorRange = ["#ff3300", "#79f304", "#0936fb", "#303134", "#cfd1db"];
    //var colorRange = ["#f1a20a", "#0af1d4", "#c60af1", "#d4da10", "#f10d96"];
    var colorRange = ["#ff3300", "#79f304", "#0936fb", "#303134", "#cfd1db", "#f1a20a", "#0af1d4", "#c60af1", "#d4da10", "#f10d96"];
    // set the color scale
    var color = d3.scaleOrdinal()
    .domain(data)
    .range(colorRange)

    // Compute the position of each group on the pie:
    var pie = d3.pie()
    .value(function(d) { return d.value.WinRatio })

    var data_ready = pie(d3.entries(data))
    var arcGenerator = d3.arc()
    .innerRadius(0)
    .outerRadius(radius)

    console.log("Data ready:");
    console.log(data_ready);
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
    .text(function(d){ return  d.data.value.WinRatio + "%" })
    .attr("transform", function(d) { return "translate(" + arcGenerator.centroid(d) + ")";  })
    .style("text-anchor", "middle")
    .style("font-size", 17)

    svg
    .selectAll('whatever')
    .data(data_ready)
    .enter()
    .append('text')
    .text(function(d){ return "(" + (d.data.value.NumWins) + " Games)"})
    .attr("transform", function(d) { var centroid = arcGenerator.centroid(d);
                                     var x = centroid[0];
                                     var y = centroid[1] + 20;
                                     return "translate(" + x + "," + y + ")";  
                                    })
    .style("text-anchor", "middle")
    .style("font-size", 14)    


    var ordinal = d3.scaleOrdinal()
    .domain(Object.keys(player_data))
    .range(colorRange);
  
    var legendXPos = width / 2;
    var legendYPos = (height /3) * -1;

    svg.append("rect")
    .attr("class", "legendBorder")
    .attr("transform", "translate(" + (legendXPos -10) + "," + (legendYPos -20) + ")")
    

    svg.append("g")
    .attr("class", "legendOrdinal")
    .attr("transform", "translate(" + legendXPos + "," + legendYPos + ")")
    ;

    var legendOrdinal = d3legend.legendColor()
    .shape("path", d3.symbol().type(d3.symbolSquare).size(150)())
    .shapePadding(10)
    .scale(ordinal)
    

    svg.select(".legendOrdinal")
    .call(legendOrdinal);

    svg.select("rect")
    .attr("height", (svg.select("g").node().getBBox().height + 20))
    .attr("width", (svg.select("g").node().getBBox().width + 20))
    .attr("style", "outline: thin solid black;fill:white")   //This will do the job


    
});