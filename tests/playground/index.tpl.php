<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Agnostic Playground</title>
    <style type="text/css">
        article header {
            margin: 1em 0;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <aside>
        <ul>
        <?php foreach ($catalog as $name => $item): ?>
            <li><a href="?<?php echo $name ?>"><?php echo $name ?></a></li>
        <?php endforeach; ?>
        </ul>
    </aside>
    <article>
    <?php if ($active_item): ?>
        <section>
            <header>Code</header>
            <pre>
                <?php echo htmlentities(file_get_contents(__DIR__.'/'.$active_item['file'])); ?>
            </pre>
        </section>
        <section>
            <header>Queries</header>
            <div>
            <?php foreach ($queries as $query): ?>
                <?php echo $query['query'] ?> (<?php echo number_format($query['time'], 6); ?>s)<br/>
            <?php endforeach; ?>
            </div>
        </section>
        <section>
            <header>Result</header>
            <?php var_dump($result->toArray()); ?>
        </section>
    <?php endif; ?>
    </article>
</body>
</html>