# Image Viewer

Progetto didattico per gestire una lista di immagini.

## Stack
Symfony 7.2, PHP 8.3, DB SQLite

## Informazioni di servizio
Al momento il progetto gira con il server di Symfony:

```
symfony server:start -d
```

## Operazioni per inizializzazione del database

```
php bin/console doctrine:schema:create
```
Il DB viene creato sotto /var/data.db.

Query da lanciare per inserire i dati delle immagini di test precedentemente caricate:

```
INSERT INTO images (id, path, creation_date, title, metadata)
VALUES 
(1, 'lago-1.jpg', '2025-04-19 10:06:04.000', 'Lago 1', '[]'),
(2, 'lago-2.jpg', '2025-04-19 10:09:33.000', 'Lago 2', '[]')
(3, 'lago-3.jpg', '2025-04-19 10:23:33.000', 'Lago 3', '[]'),
(4, 'montagna-1.jpg', '2025-04-19 11:07:24.000', 'Montagna 1', '[]'),
(5, 'montagna-2.jpg', '2025-04-19 12:10:24.000', 'Montagna 2', '[]'),
(6, 'montagna-3.jpg', '2025-04-19 13:13:24.000', 'Montagna 3', '[]'),
(7, 'montagna-4.jpg', '2025-04-19 15:13:24.000', 'Montagna 4', '[]'),
(8, 'tramonto-1.jpeg', '2025-04-20 10:08:28.000', 'Tramonto 1', '[]'),
(9, 'tramonto-2.jpeg', '2025-04-20 11:08:28.000', 'Tramonto 2', '[]'),
(10, 'tramonto-3.jpeg', '2025-04-20 12:08:28.000', 'Tramonto 3', '[]'),
(11, 'tramonto-4.jpeg', '2025-04-21 12:08:28.000', 'Tramonto 4', '[]'),
(12, 'cielo-1.jpg', '2025-04-22 14:05:04', 'Cielo 1', '[]');

INSERT INTO users (id, username) VALUES (1, 'Guest');
```