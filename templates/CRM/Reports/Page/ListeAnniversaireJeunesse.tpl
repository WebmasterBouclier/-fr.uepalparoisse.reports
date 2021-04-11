<h3>Description</h3>
<div>
	<ul>
		<li>Liste des anniversaires des enfants de moins de 18 ans, classée par âge, non décédé, non rayé du fichier.</li>
		<li>Exclusion des Individus uniquement membre de l'association.</li>
		<li>ATTENTION : les statuts "Ami de la paroisse" et "vide" sont éventuellement à enlever de la liste (à étudier au cas par cas)</li>
		<li>Affichage du nom et prénom, de l'âge, de la date de naissance, de l'adresse, du type d'adhésion, du mois de naissance et du jour de naissance.</li>
	</ul>
</div>
<h3>A quoi sert cette liste ?</h3>
<div>
	<ul>
		<li>A lister les enfants de moins de 18 ans, pour leur envoyer une carte d'anniversaire.</li>
		<li>Cible = Enfants de moins de 18 ans, inscrits à la paroisse en tant qu'Electeur ou Inscrit Enfant.</li>
	</ul>
</div>
<h3>Comment se servir de cette liste ?</h3>
<div>
	<ul>
		<li>Copier l'ensemble de la liste vers un tableur (Excel ou équivalent).</li>
		<li>Dans le tableur, trier sur la colonne Mois de naissance, puis sur la colonne Jour de naissance.</li>
	</ul>
</div>
<h3>Liste</h3>
<div>Il y a {$AnniversaireJeunesse|@count} personnes affichés</div>
<table class="report-layout display">
			<thead>
			<tr>
				<th>Nom</th>
				<th>Prénom</th>
				<th>Age</th>
				<th>Date de naissance</th>
				<th>Courriel</th>
				<th>Téléphone portable</th>
				<th>Rue</th>
				<th>Code Postal</th>
				<th>Ville</th>
				<th>Status</th>
				<th>Mois de naissance</th>
				<th>Jour de naissance</th>

			</tr>
			</thead>
			<tbody>
			{foreach from=$AnniversaireJeunesse item=row}
					<tr class="{cycle values="odd-row,even-row"}">
						<!--<td>{$row[0]}</td> Identifiant-->
						<td><a href="{crmURL p='civicrm/contact/view' q="reset=1&cid=`$row[0]`"}" target="_blank">{$row[1]}</a></td>
						<td>{$row[2]}</td>
						<td></td>
						<td>{$row[3]|date_format:"%d/%m/%Y"}</td>
						<td>{mailto address="`$row[4]`"}</td>
						<td>{$row[5]}</td>
						<!--<td>{$row[6]}</td>-->
						<td>{$row[6]}</td>
						<td>{$row[7]}</td>
						<td>{$row[8]}</td>
						<td>{$row[9]}</td>
						<td>{$row[3]|date_format:"%m"}</td>
						<td>{$row[3]|date_format:"%d"}</td>
					</tr>
				{/foreach}
					</tbody>
		</table>
