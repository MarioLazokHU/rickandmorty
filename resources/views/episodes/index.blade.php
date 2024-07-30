<!DOCTYPE html>
<html>

<head>
    <title>Rick and Morty</title>
</head>

<body>
    <main>
        <h1>Epizódok</h1>
        <form action="{{ url('episodes/search') }}" method="GET">
            <input type="text" name="searchtext" placeholder="Név vagy adásba kerülés alapján keresés" />
            <button type="submit">Keresés</button>
            <a href="/">Vissza</a>
        </form>

        <table style="width: 50%;">
            <thead>
                <tr>
                    <th>
                        <a
                            href="{{ '?' . http_build_query(array_merge(request()->except(['orderDirection']), ['orderDirection' => request('orderDirection') === 'asc' ? 'desc' : 'asc', 'orderBy' => 'name'])) }}">
                            Név
                        </a>
                    </th>
                    <th>
                        <a
                            href="{{ '?' . http_build_query(array_merge(request()->except(['orderDirection']), ['orderDirection' => request('orderDirection') === 'asc' ? 'desc' : 'asc', 'orderBy' => 'air_date'])) }}">
                            Megjelenés
                        </a>
                    </th>
                    <th>
                        <a
                            href="{{ '?' . http_build_query(array_merge(request()->except(['orderDirection']), ['orderDirection' => request('orderDirection') === 'asc' ? 'desc' : 'asc', 'orderBy' => 'episode'])) }}">
                            Epizód
                        </a>
                    </th>
                    <th>Szereplők</th>
                </tr>
            </thead>
            <tbody align="center">
                @foreach ($episodes as $episode)
                    <tr>
                        <td>{{ $episode->name }}</td>
                        <td>{{ $episode->air_date }}</td>
                        <td>{{ $episode->episode }}</td>
                        <td>
                            <button class="show-characters" data-episode-id={{ $episode->id }}>Szereplők</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div>
            {{ $episodes->links() }}
        </div>

        <div id="characterModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Characters</h2>
                <div id="charactersList">

                </div>
            </div>
        </div>

    </main>
</body>
<script defer>
    const modal = document.getElementById("characterModal");
    const span = document.querySelector(".close");


    async function openModal(characters) {
        const charactersList = document.getElementById("charactersList");
        charactersList.innerHTML = '';

        characters.forEach((character) => {
            const div = document.createElement("div");
            div.innerHTML = `<p>Name: ${character.name}</p>`;
            charactersList.appendChild(div);
        });

        modal.style.display = "block";
    }


    span.addEventListener('click', () => {
        modal.style.display = "none";
    })

    document.addEventListener('click', (event) => {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    })

    const charactersBtn = document.querySelectorAll('.show-characters')
    charactersBtn.forEach((button) => {
        button.addEventListener('click', async () => {
            const req = await fetch(
                `/episodes/${button.getAttribute('data-episode-id')}/characters`)
            const res = await req.json()
            if (res) {
                await openModal(res);
            }


        });
    });
</script>

</html>

<style>
    main {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        height: 100%;
        gap: 50px;
    }

    .modal {
        display: none;
        position: absolute;
        z-index: 1;
        left: 50%;
        top: 60%;
        transform: translate(-50%, -50%);
        overflow-y: auto;
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }


    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
</style>
