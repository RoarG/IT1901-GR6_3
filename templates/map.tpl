[[+include file="header.tpl"]]
<div id="sheep_json" style="display: none;">[[+$sheep_json]]</div>
<div id="map_json" style="display: none;">[[+$map_json]]</div>
<div id="map_controlls">
    <h2>Simulering</h2>
    <input type="button" value="Skru på" id="sim_toggle" name="sim_toggle" />
    <div id="map_controlls_inner">
        <p>Fart: <div id="speed_holder"><div id="speed"></div></div><span id="speed_val">20x</span>
        <p>1 min.: <span id="sim_one_min_eq"></span></p>
        <p>Klokka: <span id="sim_clock"></span>
    </div>
</div>
<div id="map"></div>
[[+include file="footer.tpl"]]