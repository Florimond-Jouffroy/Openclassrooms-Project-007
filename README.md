# BileMo
Project 7 - API REST BileMo - OCR

<h3>Documentation</h3>
<p>L'ensemble du code source a été commenté. L'utilsation de PhpDocBlocker a permis de générer une documentation claire et précise.</p>
<h3>Langage de programmation</h3>

<li>L'API REST BileMo a été développé en PHP via le framework Symfony 5.4</li>
<li>L'utilisation de librairy telles que nelmio, JMSSerializer et Hateoas ont été utilisées pour gérer l'ensemble des contraintes associées à la création d'une API REST.

<hr>
<h2>Installation</h2>
<h3>Environnement nécessaire</h3>
<ul>
  <li>Symfony 5.4.*</li>
  <li>PHP 7.2.*</li>
  <li>MySql 8</li>
</ul>
<h3>Suivre les étapes suivantes :</h3>
<ul>
  <li><b>Etape 1.1 :</b> Cloner le repository suivant depuis votre terminal :</li>
  <pre>
  <code>git clone https://github.com/Florimond-Jouffroy/Openclassrooms-Project-007.git</code></pre>
  <li>
   <li><b>Etape 1.2 :</b> Executer la commande suivante :</li>
  <pre>
  <code>composer install</code></pre>
  <li><b>Etape 2 :</b> Editer le fichier .env </li>
    - pour renseigner vos paramètres de connexion à votre base de donnée dans la variable DATABASE_URL
  <li><b>Etape 3 :</b> Démarrer votre environnement local (Par exemple : Wamp Server)</li>
  <li><b>Etape 4 :</b> Exécuter les commandes symfony suivantes depuis votre terminal</li>
  <pre><code>
    symfony console doctrine:database:create (ou php bin/console d:d:c si vous n'avez pas installé le client symfony)<br/>
    symfony console doctrine:migrations:migrate<br/>
    symfony console doctrine:fixtures:load
  </code></pre>


  <li><b>Etape 5 :</b>Le compte par défault est "admin@bilemo.com" avec pour mot de passe "admin"
  </li>
  <li>Vous pouvez utiliser jeton bearer suivant : eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJhZG1pbkBiaWxlbW8uY29tIn0.3khGFoAuoZT8tSbs5puJ2YisK_jpyCFf-0seRSyuQ6U</li>

</pre>
</ul>

<h3>Vous êtes fin prêt pour tester votre API!</h3>
<p>Pour afficher la doucmentation en ligne et tester l'API rendez-vous à l'adresse suivante : http://localhost:8000/api/doc <em></em></p>
