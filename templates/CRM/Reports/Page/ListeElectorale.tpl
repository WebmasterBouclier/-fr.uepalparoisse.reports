
<h3>Liste électorale : {$ListeElectorale|@count} électeurs</h3>
<div>TO DO : rajouter les numéros de téléphone du Foyer</div>


	<table class="report-layout display">
				<thead>
				<tr>
					<th>Nom</th>
					<th>Prénom</th>
					<th>Date de naissance</th>
					<th>Courriel</th>
					<th>Téléphone portable</th>
					<th>Téléphone fixe</th>
					<th>Rue</th>
					<th>Code Postal</th>
					<th>Ville</th>
					<th>Pays</th>
				</tr>
				</thead>
				<tbody>
				{foreach from=$ListeElectorale item=row}
						<tr class="{cycle values="odd-row,even-row"}">
							<!--<td>{$row[0]}</td> Identifiant-->
							<!--<td>{$row[1]}</td> Display Name-->
							<td><a href="{crmURL p='civicrm/contact/view' q="reset=1&cid=`$row[0]`"}" target="_blank">{$row[2]}</a></td>
							<td>{$row[3]}</td>
							<td>{$row[4]}</td>
							<td>{mailto address="`$row[5]`"}</td>
							<td>{$row[6]}</td>
							<!--<td>{$row[7]}</td>-->
							<td>{$row[8]}</td>
							<td>{$row[9]}</td>
							<td>{$row[10]}</td>
							<td>{$row[11]}</td>
							<td>{$row[12]}</td>
						</tr>
					{/foreach}
						</tbody>
			</table>

