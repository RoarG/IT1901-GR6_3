[[+include file="header.tpl"]]
<div id="main">
    <h1>Stats</h1>
    
    <p>Noen tall for å vise innholdet i de forskjellige systemene.</p>
    
    <table>
        <tr>
            <th>&nbsp;</th>
            <th>Dette systemet</th>
            <th>Alle systemer</th>
        </tr>
        <tr>
            <td><b>Antall systemer</b></td>
            <td>1</td>
            <td>[[+$all_system]]</td>
        </tr>
        <tr>
            <td><b>Antall sauer</b></td>
            <td>[[+$local_sheep]]</td>
            <td>[[+$all_sheep]]</td>
        </tr>
        <tr>
            <td><b>Antall levende sauer</b></td>
            <td>[[+$local_alive]]</td>
            <td>[[+$all_alive]]</td>
        </tr>
        <tr>
            <td><b>Antall døde sauer</b></td>
            <td>[[+$local_dead]]</td>
            <td>[[+$all_dead]]</td>
        </tr>
        <tr>
            <td><b>Antall brukere</b></td>
            <td>[[+$local_user]]</td>
            <td>[[+$all_user]]</td>
        </tr>
        <tr>
            <td><b>Antall notifications</b></td>
            <td>[[+$local_notifications]]</td>
            <td>[[+$all_notifications]]</td>
        </tr>
        <tr>
            <td><b>Antall uleste notifications</b></td>
            <td>[[+$local_unread]]</td>
            <td>[[+$all_unread]]</td>
        </tr>
        <tr>
            <td><b>Antall logginnlegg</b></td>
            <td>[[+$local_log]]</td>
            <td>[[+$all_log]]</td>
        </tr>
    </table>
</div>
[[+include file="footer.tpl"]]