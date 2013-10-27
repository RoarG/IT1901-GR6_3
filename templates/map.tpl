[[+include file="header.tpl"]]
<div id="sheep_json" style="display: none;">[[+$sheep_json]]</div>
<div id="map_json" style="display: none;">[[+$map_json]]</div>
<div id="map_controlls">
    <h2>Simulering</h2>
    <input type="button" value="Skru på" id="sim_toggle" name="sim_toggle" />
    <p>Fart: <input type="number" id="sim_speed" name="sim_speed" value="20" /><span id="fart_x">x</span>
    <p>1 min. tilsvarer: <span id="sim_one_min_eq"></span></p>
    <p>Tid: <span id="sim_clock"></span>
</div>
<div id="map"></div>
[[+include file="footer.tpl"]]