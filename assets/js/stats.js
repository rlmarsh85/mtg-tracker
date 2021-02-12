var d3 = require('d3');
var d3legend = require('d3-svg-legend');
const { merge } = require('jquery');


jQuery(document).ready(function() {


    // set the dimensions and margins of the graph
    var width = 450;
    var height = 550;
    var margin = 40;  

    /**
     * 
     * Setup Pie Chart
     */
    var pie_svg = d3.select("#pie_chart_placeholder")
    .append("svg")
    .attr("width", width + 200)
    .attr("height", height)
    .append("g")
    .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

    var pie_div = d3.select("body").append("div")
    .attr("class", "tooltip-pie")
    .style("opacity", 0);      
        
    updatePieChart(player_overall_data, pie_svg, pie_div, $('#pie-wins-by-player').text() );


    /**
     * 
     * Setup Bar Graph
     */
    var bar_svg = d3.select("#bar_chart_placeholder")
    .append("svg")
    .attr("width", width + 200)
    .attr("height", height)
    .append("g")

    var bar_div = d3.select("body").append("div")
    .attr("class", "tooltip-bars")
    .style("opacity", 0);    
 
    updateBarChartPercent(player_data, "Players By Win Rate", bar_svg, bar_div)

    /**
     * 
     * Setup button events
     */
    $('#pie-wins-by-player').on('click', function(){ updatePieChart(player_overall_data, pie_svg, pie_div, this.innerHTML ) });
    $('#pie-wins-by-color').on('click', function(){ updatePieChart(color_overall_data, pie_svg, pie_div, this.innerHTML ) });
    $('#pie-wins-by-deck').on('click', function(){ updatePieChart(deck_overall_data, pie_svg, pie_div, this.innerHTML ) });
    $('#pie-wins-by-commander').on('click', function(){ updatePieChart(commander_overall_data, pie_svg, pie_div, this.innerHTML ) });

    $('#bar-win-rate-player').on('click', function(){ updateBarChartPercent(player_data, this.innerHTML, bar_svg, bar_div) });
    $('#bar-win-rate-color').on('click', function(){ updateBarChartPercent(color_data, this.innerHTML, bar_svg, bar_div) });
    $('#bar-win-rate-deck').on('click', function(){ updateBarChartPercent(deck_data, this.innerHTML, bar_svg, bar_div) });
    $('#bar-win-rate-commander').on('click', function(){ updateBarChartPercent(commander_data, this.innerHTML, bar_svg, bar_div) });

    $('#bar-popular-decks').on('click', function(){ updateBarChartRaw(most_popular_decks, this.innerHTML, bar_svg, bar_div) });
    $('#bar-popular-commanders').on('click', function(){ updateBarChartRaw(most_popular_commanders, this.innerHTML, bar_svg, bar_div) });
    $('#bar-popular-colors').on('click', function(){ updateBarChartRaw(most_popular_colors, this.innerHTML, bar_svg, bar_div) });
    $('#bar-ramped_players').on('click', function(){ updateBarChartRaw(most_ramped_players, this.innerHTML, bar_svg, bar_div,"Number Ramps",function(d){ return d.NumRamps} ) });
    
});

function updateBarChartRaw(data, title, svg, div,yLabel="Number Plays",dataReturnFunc=function(d){ return d.NumPlays}){

  updateBarChart(
    data, 
    svg, 
    div, 
    title,
    dataReturnFunc, 
    dataReturnFunc,
    function(data){ 
      var max = (Math.max.apply(Math, Object.values(data).map(function(o) { return dataReturnFunc(o); })));
      max = (max < 10) ? 10: max;
      return [0, max];
    },
    10,
    "s",
    yLabel
  );       
}

function updateBarChartPercent(data, title, svg, div){
  updateBarChart(
    data, 
    svg, 
    div, 
    title, 
    function(d){ return d.WinRatio},
    function(d){ return d.NumWins}, 
    function(data){ return [0,100]},
    20,
    "%",
    "Win Ratio" 
  );

}

function splitName(d){

  var el = d3.select(this);
  var words = d.match(/.{1,15}/g)
  el.text('');

  for (var i = 0; i < words.length; i++) {
      var tspan = el.append('tspan').text(words[i] + ((i == (words.length - 1)) ? "" : "-") );
      if (i > 0)
          tspan.attr('x', 0).attr('dy', '15');
  }
    
}

function updateBarChart(data, svg, float_div, title, dataReturnFunc, floatReturnFunc, domainFunc, numTicks, tickFormat, yLabel){

  var width = 450;
  var height = 450;
  var margin = { top: 20, right: 20, bottom: 30, left: 40 };   
  var dataTableClassName = "bar-chart-table"

  svg.selectAll("*").remove();
  clearDataTable(dataTableClassName);

  svg
  .append('text')
  .text(title)
  .attr("class", "graph-title")
  .style("text-anchor", "left")
  .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
  
  var x = d3.scaleBand()
    .padding(0.1)
    .rangeRound([15, width])
    .domain(Object.values(data).map(function(item) { return item.Name ; } ));

  

  y = d3.scaleLinear()
      .rangeRound([height, 0]);
  
  if(tickFormat == "s"){
    y.domain(domainFunc(data))
  }

  var g = svg.append("g")
    .attr("transform", "translate(" + margin.left + "," + (margin.top * 2) + ")");
    

  g.append("g")
  .attr("class", "axis axis--x");

  g.append("g")
    .attr("class", "axis axis--y");

  g.append("text")
    .attr("transform", "rotate(-90)")
    .attr("y", 6)
    .attr("dy", "0.71em")
    .attr("text-anchor", "end")
    .text(yLabel);


  g.select(".axis--x")
  .attr("transform", "translate(0," + height + ")")
  .call(d3.axisBottom(x))
  
  .call(g => g.selectAll(".tick text").each(splitName)) 
  

  g.select(".axis--y")
    .call(d3.axisLeft(y).ticks(numTicks, tickFormat));

  if(tickFormat == "%"){
    y.domain(domainFunc(data))
  }    



  var bars = g.selectAll(".bar")
    .data(Object.values(data))
    
  // ENTER    



  bars
    .enter().append("rect")
    .attr("class", function(d){ var color=resolveColor(d.Name); return  color + " bar"})
    .attr("x", function (d) { return x(d.Name); })
    .attr("y", function (d) { return y(dataReturnFunc(d)); })
    .attr("width", x.bandwidth())
    .attr("height", function (d) { return height - y(dataReturnFunc(d)); });

  
  addHoverEffect(svg,".bar",float_div, function(d){ return floatReturnFunc(d)});

  addDataTable('bar_chart_placeholder',dataTableClassName,data);


}

function updatePieChart(data, svg, float_div, title) {
  
  // set the dimensions and margins of the graph
  var width = 450;
  var height = 450;
  var margin = 40; 
  
  var dataTableClassName = 'pie-chart-table';

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
  clearDataTable(dataTableClassName)

  svg
  .append('text')
  .text(title)
  .attr("class", "graph-title")
  .style("text-anchor", "left")
  .attr("transform", "translate(-" + ((width / 2) -10) + ", -" + ( height/2 + 30 ) + ")" );

    
  // Build the pie chart: Basically, each part of the pie is a path that we build using the arc function.
  svg.selectAll("path")
  .data(data_ready)
  .enter()
  .append('path')
  .merge(svg.selectAll("path"))
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
  .style("opacity", 0.8);


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
  .style("font-size", 17);


  addHoverEffect(svg, 'path', float_div, function(d){ return d.data.value.NumWins});

  /**
   * 
   * Adding Legend
   */

  var ordinal = d3.scaleOrdinal()
  .domain(Object.keys(data))
  .range(colorRange);

  var legendXPos = width / 2;
  var legendYPos = ((height /3) * -1) - 50;

  svg.append("rect")
  .attr("class", "legendBorder")
  .attr("transform", "translate(" + (legendXPos -10) + "," + (legendYPos -20) + ")")
  
  
  svg.append("g")
  .attr("class", "legendOrdinal")
  .attr("transform", "translate(" + legendXPos + "," + legendYPos + ")")
  ;
  
  
  var legendOrdinal = d3legend.legendColor()
  .shape("path", d3.symbol().type(d3.symbolSquare).size(150)())
  .shapePadding(20)
  .scale(ordinal)
  .labelWrap(90)
  .labelAlign("start");

  svg.select(".legendOrdinal")
  .call(legendOrdinal);

  svg.select(".legendOrdinal").selectAll(".swatch")
  .attr("class", function(d){ return "swatch " + resolveColor(d)} );  



  svg.select("rect")
  .attr("height", (svg.select("g").node().getBBox().height + 20))
  .attr("width", (svg.select("g").node().getBBox().width + 20))
  .attr("style", "outline: thin solid black;fill:white")
  ;

  addDataTable('pie_chart_placeholder', dataTableClassName, data);
  
}



function clearDataTable(class_name){
  d3.selectAll('.' + class_name).remove();
  d3.selectAll('.data-table-control-' + class_name).remove();
}

function addDataTable(parent_id, class_name, data){

  var parent_control_div = d3.select("#" + parent_id).append("div");
  
  parent_control_div.append("div")
  .attr('class', 'data-table-control data-table-control-' + class_name)
  .html("Show Raw Data")
  .on('click', function(d,i){
    d3.select('.' + class_name)
      .style('display', 'block')
  })

  parent_control_div.append("div")
  .attr('class', ' data-table-control data-table-control-' + class_name)
  .html("Hide Raw Data")
  .on('click', function(d,i){
    d3.select('.' + class_name)
    .style('display', 'none')
  });      


  var sortAscending = true;
  var table = d3.select("#" + parent_id).append('table').attr('class',class_name + " table-hover table");
  var titles = Object.keys(data[Object.keys(data)[0]]);

  var headers = table.append('thead').append('tr')
  .selectAll('th')
  .data(titles).enter()
  .append('th')
  .text(function (d) {
    return d;
  })
  .on('click', function (d) {
  headers.attr('class', 'header');
  if (sortAscending) {
    rows.sort(function(a, b) { return b[d] < a[d]; });
    sortAscending = false;
    this.className = 'aes';        
  }else{
    rows.sort(function(a, b) { return b[d] > a[d]; });
    sortAscending = true;
    this.className = 'des';        
  }

  });

  var rows = table.append('tbody').selectAll('tr')
  .data(Object.values(data)).enter()
  .append('tr');

  rows.selectAll('td')
  .data(function (d) {
  return titles.map(function (k) {
    return { 'value': d[k], 'name': k};
    });      
  }).enter()
  .append('td')
  .attr('data-th', function (d) {
    return d.name;
  })
  .text(function (d) {
    return d.value;
  });          
}

function addHoverEffect(svg, select_clause, float_div, callback){

  svg.selectAll(select_clause)
  .on('mouseover', function (d, i) {

    var num = callback(d) + " game" + ((callback(d) == 1) ? "" : "s");
    d3.select(this).transition()
         .duration(1)
         .attr('opacity', '.85');

         float_div.transition()
         .duration(1)
         .style("opacity", 1);

         float_div.html(num)
         .style("left", (d3.event.pageX + 10) + "px")
         .style("top", (d3.event.pageY - 15) + "px");           
  })
  .on('mouseout', function (d, i) {
    d3.select(this).transition()
         .duration(1)
         .attr('opacity', '1');

    float_div.transition()
    .duration(1)
    .style("opacity", 0);                  
  });

}

function resolveColor(name){
  name = name.toLowerCase();
  if(name == "red" || name == "blue" || name == "black" || name == "green" || name == "white" || color_identities.includes(name)){
    return name;
  }
  
  return "";
}