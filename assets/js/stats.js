var d3 = require('d3');
var d3legend = require('d3-svg-legend');

function resolveColor(name){
  name = name.toLowerCase();
  if(name == "red" || name == "blue" || name == "black" || name == "green" || name == "white"){
    return name;
  }
  
  return "";
}

jQuery(document).ready(function() {


    // set the dimensions and margins of the graph
    var width = 450;
    var height = 450;
    var margin = 40;  

    
    var pie_svg = d3.select("#pie_chart_placeholder")
    .append("svg")
    .attr("width", width + 200)
    .attr("height", height)
    .append("g")
    .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

    updatePieChart(player_data,pie_svg)
    
    

    
    var bar_svg = d3.select("#bar_chart_placeholder")
    .append("svg")
    .attr("width", width + 200)
    .attr("height", height)
    .append("g")
    //.attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

    updateBarChart(player_data,bar_svg); 


    $('#data1_btn').on('click', function(){ updatePieChart(player_data,pie_svg) });
    $('#data2_btn').on('click', function(){ updatePieChart(color_data,pie_svg) });

    $('#bar_data1_btn').on('click', function(){ updateBarChart(player_data,bar_svg) });
    $('#bar_data2_btn').on('click', function(){ updateBarChart(color_data,bar_svg) });    

});

function updateBarChart(data,svg){

  var width = 450;
  var height = 400;
  var margin = { top: 20, right: 20, bottom: 30, left: 40 };   

  svg.selectAll("*").remove();
  
  var x = d3.scaleBand().padding(0.1),
  y = d3.scaleLinear();  

  var g = svg.append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
    

  g.append("g")
  .attr("class", "axis axis--x");

  g.append("g")
    .attr("class", "axis axis--y");

  g.append("text")
    .attr("transform", "rotate(-90)")
    .attr("y", 6)
    .attr("dy", "0.71em")
    .attr("text-anchor", "end")
    .text("Win Ratio");


  x.rangeRound([15, width]);
  y.rangeRound([height, 0]);

  x.domain(Object.values(data).map(function(item) { return item.Name; } ));

  g.select(".axis--x")
  .attr("transform", "translate(0," + height + ")")
  .call(d3.axisBottom(x));

  g.select(".axis--y")
    .call(d3.axisLeft(y).ticks(20, "%"));

  y.domain([0, 100]);

  var bars = g.selectAll(".bar")
    .data(Object.values(data));
    
  // ENTER    


  bars
    .enter().append("rect")
    .attr("class", function(d){ var color=resolveColor(d.Name); return  color + " bar"})
    .attr("x", function (d) { return x(d.Name); })
    .attr("y", function (d) { return y(d.WinRatio); })
    .attr("width", x.bandwidth())
    .attr("height", function (d) { return height - y(d.WinRatio); })


}

function updatePieChart(data,svg) {
  
  // set the dimensions and margins of the graph
  var width = 450;
  var height = 450;
  var margin = 40;  

  // The radius of the pieplot is half the width or half the height (smallest one). I subtract a bit of margin.
  var radius = Math.min(width, height) / 2 - margin  

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

  var u = svg.selectAll("path")
    .data(data_ready)

  svg.selectAll('text').remove();
  svg.select(".legendOrdinal").remove();

  // Build the pie chart: Basically, each part of the pie is a path that we build using the arc function.
  u
    .enter()
    .append('path')
    .merge(u)
    .transition()
    .duration(1000)
    .attr('d', d3.arc()
      .innerRadius(0)
      .outerRadius(radius)
    )
    .attr('fill', function(d){ return(color(d.data.key)) })
    .attr('class', function(d){ return "pie-slice " + resolveColor(d.data.key); } )
    .attr("stroke", "white")
    .style("stroke-width", "2px")
    .style("opacity", 0.8)

  // remove the group that is not present anymore
  u
    .exit()
    .remove()

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
  .domain(Object.keys(data))
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
  .scale(ordinal);

  svg.select(".legendOrdinal")
  .call(legendOrdinal);

  svg.select(".legendOrdinal").selectAll(".swatch")
  .attr("class", function(d){ return "swatch " + resolveColor(d)} );  



  svg.select("rect")
  .attr("height", (svg.select("g").node().getBBox().height + 20))
  .attr("width", (svg.select("g").node().getBBox().width + 20))
  .attr("style", "outline: thin solid black;fill:white")
  ;
  
}