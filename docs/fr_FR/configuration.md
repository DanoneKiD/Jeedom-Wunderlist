# Configuration


## Configuration du plugin
Après téléchargement du plugin, il faut l’activer, celui-ci ne nécessite aucune autre configuration. 

![Config](../images/doc_config.png)

## Configuration de l'API Wunderlist
> **Note**
>
> Pour pouvoir intéragir avec vos listes, il faut au préalable créer une
> "**APP**" sur le site de Wunderlist et obtenir 3 informations qui nous
> serviront pour la suite. 

Allez sur <https://developer.wunderlist.com/apps> et connectez-vous avec
votre compte Wunderlist (vous pouvez aussi utiliser **Facebook**,
**Google** ou **Microsoft**).

![..\\images\\config api 5a65b](../images/config-api-5a65b.png)

Vous tombez sur une page ou vous pouvez créer votre **APP**.

![..\\images\\config api 88a5d](../images/config-api-88a5d.png)

1.  Le nom de votre APP ex : **Jeedom**

2.  Une url quelconque **<http://localhost>**

3.  Idem pour l’URL de Callback **<http://localhost>**

4.  Cliquez sur **Save**

![..\\images\\config api adea4](../images/config-api-adea4.png)

Vous tombez sur cette page ou il faudra relever le **Client ID**, le
**Client Secret** et générer un **Access Token** en cliquant sur
**Create Access Token**.

![..\\images\\config api 3b049](../images/config-api-3b049.png)

## Configuration des équipements

Allez sur la page du plugin : **Plugins** → **Organisation** →
**Wunderlist**

![..\\images\\config eq 99022](../images/config-eq-99022.png)

Cliquez sur **Ajouter**

![..\\images\\config eq c9318](../images/config-eq-c9318.png)

Saisissez un **nom** pour votre liste

![..\\images\\config eq d8168](../images/config-eq-d8168.png)

![..\\images\\config eq c9740](../images/config-eq-c9740.png)

1.  Le nom de votre liste

2.  Activer l’équipement

3.  Saisissez le **Client ID** relevé précédemment sur le site
    <https://developer.wunderlist.com/apps>

4.  Le **Client Secret**

5.  l'**Access Token**

Cliquez ensuite sur **Sauvegarder** pour vous connecter à Wunderlist et
ainsi récupérer vos listes.

![..\\images\\config eq 2b6d2](../images/config-eq-2b6d2.png)

Sélectionnez votre liste et re-cliquer sur **Sauvegarder**

Vous avez maintenant à votre disposition 3 commandes Actions que vous
pouvez utiliser dans Jeedom (ex : dans des scénarios).

![..\\images\\config eq 9eb14](../images/config-eq-9eb14.png)