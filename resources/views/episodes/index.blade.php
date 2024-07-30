<head>
    <title>Rick and Morty</title>
</head>

<body>
    <main>
        <h1>Rick és Morty Epizódlista</h1>
        <h2>Epizódok</h2>
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
                        <i class="fas {{ request('orderDirection') === 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>
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
    span.addEventListener('click', () => {
        modal.style.display = "none";
    })

    async function openModal(characters) {
        const charactersList = document.getElementById("charactersList");
        charactersList.innerHTML = '';

        characters.forEach((character) => {
            const characterCon = document.createElement("div");
            characterCon.classList.add('character-con')

            const characterName = document.createElement('p')
            characterName.textContent = `Név: ${character.name}`

            const characterSpecies = document.createElement('p')
            characterSpecies.textContent = `Faj: ${character.species}`

            const characterGender = document.createElement('p')
            characterGender.textContent = `Nem: ${character.gender}`

            const characterImg = document.createElement('img')
            characterImg.src = character.image

            characterCon.append(characterName, characterSpecies, characterGender, characterImg)
            charactersList.appendChild(characterCon);
        });

        modal.style.display = "block";
    }


    const charactersBtn = document.querySelectorAll('.show-characters')
    charactersBtn.forEach((button) => {
        button.addEventListener('click', async () => {
            const req = await fetch(
                `/episodes/${button.getAttribute('data-episode-id')}/characters`)
            const res = await req.json()
            if (res) {
                console.log(res)
                await openModal(res);
            }


        });
    });
</script>
<style>
    body {
        background-color: rgb(48, 47, 47);
        color: white
    }

    img {
        width: 100px;
        height: auto;
        box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.527);
        border-radius: 3px
    }

    form {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px
    }

    input {
        background-color: transparent;
        border: none;
        border-bottom: 2px solid #3d4a5c;
        padding: 5px;
        width: 300px;
        outline: none;
        color: white
    }

    input::placeholder {
        color: rgb(133, 150, 189);
    }

    button {
        background: #3d4a5c;
        color: white;
        border: none;
        padding: 5px;
        border-radius: 3px;
        box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.432)
    }

    a {
        text-decoration: none;
        color: rgb(133, 150, 189);
    }

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
        background-color: #3b3b3b;
        transform: translate(-50%, -50%);
        overflow-y: auto;
        box-shadow: 0px 0px 40px rgba(0, 0, 0, 0.541);
        border-radius: 5px
    }

    .modal-content {
        padding: 20px;
        width: 80%;
        width: 600px;
        max-height: 600px;
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

    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: transparent;
    }

    ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .character-con {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-template-rows: 1fr;
        grid-column-gap: 0px;
        grid-row-gap: 0px;
        padding: 5px;
        border-bottom: 1px solid #ffffff50
    }
</style>
