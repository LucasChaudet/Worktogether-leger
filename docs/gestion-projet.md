# Gestion de projet – WorkTogether

- Dépôt GitHub : https://github.com/LucasChaudet/Worktogether-leger
- Tableau Kanban (GitHub Project) : https://github.com/users/LucasChaudet/projects/2

## Outils utilisés

| Outil | À quoi il sert |
|---|---|
| **Issues GitHub** | Une issue = une tâche. Elle contient la liste des étapes à faire (cases à cocher). |
| **Labels** | Ranger les tâches par thème (installation, base de données, authentification...). |
| **GitHub Project (Kanban)** | Voir où en est chaque tâche : à faire, en cours, terminée. |
| **Vue Roadmap (Gantt)** | Voir les tâches sur un calendrier grâce aux dates de début et de fin. |

## Fonctionnement du Kanban

Le tableau a trois colonnes :

- **Todo** : la tâche est prévue mais pas commencée.
- **In Progress** : je travaille dessus en ce moment.
- **Done** : la tâche est terminée.

Règles que je suis :

1. Je ne travaille que sur une ou deux tâches à la fois (colonne In Progress).
2. Quand je commence une tâche, je la déplace dans In Progress.
3. Quand toutes les cases de la tâche sont cochées, je ferme l'issue : elle passe dans Done.
4. Je mets le tableau à jour à chaque séance.

## Découpage du projet

Le travail demandé est découpé en 5 grandes parties, puis en tâches plus petites.

| N° | Partie | Tâche | Statut |
|---|---|---|---|
| 1 | Installation | Installer le projet Symfony | Terminée |
| 2 | Installation | Créer le dépôt GitHub | Terminée |
| 3 | Conception | Faire le diagramme de classes | Terminée |
| 4 | Installation | Installer la base de données avec Docker | Terminée |
| 5 | Base de données | Créer les entités | Terminée |
| 6 | Base de données | Créer les fixtures | Terminée |
| 7 | Gestion de projet | Organiser le projet dans un tableau Kanban | En cours |
| 8 | Espace client | Faire la mise en page commune du site | À faire |
| 9 | Authentification | Se connecter et se déconnecter | À faire |
| 10 | Authentification | Créer un compte client | À faire |
| 11 | Espace client | Afficher les unités du client | À faire |
| 12 | Espace client | Modifier le nom et le type d'une unité | À faire |
| 13 | Espace client | Afficher les baies du client | À faire |
| 14 | Espace client | Afficher les commandes du client | À faire |
| 15 | Suite (situation 2) | Présenter la société et les offres | À faire |
| 16 | Suite (situation 2) | Passer une commande | À faire |
| 17 | Suite (situation 2) | Modifier ses informations personnelles | À faire |
| 18 | Suite (situation 2) | Créer l'API pour les clients | À faire |

Les tâches 1 à 14 correspondent au travail demandé pour démarrer le projet. Les tâches 15 à 18 préparent la suite de la situation professionnelle 2.

## Planning (Gantt)

```mermaid
gantt
    title Planning du projet WorkTogether
    dateFormat YYYY-MM-DD
    axisFormat %d/%m

    section Installation
    Installer le projet Symfony            :done, t1, 2026-10-15, 1d
    Créer le dépôt GitHub                  :done, t2, 2026-10-16, 1d
    Installer la base de données (Docker)  :done, t4, 2026-10-16, 1d

    section Conception
    Diagramme de classes                   :done, t3, 2026-10-15, 2d

    section Base de données
    Créer les entités                      :done, t5, 2026-10-16, 1d
    Créer les fixtures                     :done, t6, 2026-10-16, 1d

    section Gestion de projet
    Tableau Kanban et planning             :active, t7, 2026-10-22, 2d

    section Authentification
    Mise en page commune du site           :t8, 2026-10-23, 2026-10-26
    Se connecter et se déconnecter         :t9, 2026-10-26, 2026-10-29
    Créer un compte client                 :t10, 2026-10-29, 2026-11-02

    section Espace client
    Afficher les unités du client          :t11, 2026-11-02, 2026-11-05
    Modifier le nom et le type d'une unité :t12, 2026-11-05, 2026-11-09
    Afficher les baies du client           :t13, 2026-11-09, 2026-11-13
    Afficher les commandes du client       :t14, 2026-11-13, 2026-11-16

    section Suite (situation 2)
    Présenter la société et les offres     :t15, 2026-11-16, 2026-11-20
    Passer une commande                    :t16, 2026-11-20, 2026-11-30
    Modifier ses informations              :t17, 2026-11-30, 2026-12-04
    API pour les clients                   :t18, 2026-12-04, 2026-12-18
```

Les dates sont des prévisions : je les ajuste dans le GitHub Project si une tâche prend plus ou moins de temps que prévu.
