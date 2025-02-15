<nav class="horizontal-nav container hidden-xs" data-tray-tst="menu_categorias">
    <ul class="nivel1" data-tray-tst="categoria">

        {% for nivel1 in categories %}

        {% if categories|length > 6 %}

        {% if loop.index <= 5 %}
        <li class=" {{ loop.index > 3 ? 'sub-left' }}">
            <a href="{{ nivel1.link }}" class="link-nivel1" data-tray-tst="categoria_lvl_1">
                <span>{{ nivel1.name }}</span>
            </a>

            {% if nivel1.children  %}
            <ul class="nivel2">
                {% for nivel2 in nivel1.children %}
                <li class="item-nivel2">
                    <a href="{{ nivel2.link }}" class="link-nivel2" data-tray-tst="categoria_lvl_2">
                        <span>{{ nivel2.name }}</span>
                    </a>

                    {% if nivel2.children  %}
                    <ul class="nivel3">
                        {% for nivel3 in nivel2.children %}
                        <li class="item-nivel3">
                            <a href="{{ nivel3.link }}" class="link-nivel3" data-tray-tst="categoria_lvl_3">
                                <span>{{ nivel3.name }}</span>
                            </a>
                        </li>
                        {% endfor %}
                    </ul>
                    {% endif %}
                </li>
                {% endfor %}
            </ul>
            {% endif %}
        </li>
        {% endif %}

        {% else %}

        <li class="item-nivel1 col-sm-2 {{ loop.index > 3 ? 'sub-left' }}">
            <a href="{{ nivel1.link }}" class="link-nivel1" data-tray-tst="categoria_lvl_1">
                <span>{{ nivel1.name }}</span>
            </a>

            {% if nivel1.children  %}
            <ul class="nivel2">
                {% for nivel2 in nivel1.children %}
                <li class="item-nivel2">
                    <a href="{{ nivel2.link }}" class="link-nivel2" data-tray-tst="categoria_lvl_2">
                        <span>{{ nivel2.name }}</span>
                    </a>

                    {% if nivel2.children  %}
                    <ul class="nivel3">
                        {% for nivel3 in nivel2.children %}
                        <li class="item-nivel3">
                            <a href="{{ nivel3.link }}" class="link-nivel3" data-tray-tst="categoria_lvl_3">
                                <span>{{ nivel3.name }}</span>
                            </a>
                        </li>
                        {% endfor %}
                    </ul>
                    {% endif %}
                </li>
                {% endfor %}
            </ul>
            {% endif %}
        </li>

        {% endif %}

        {% endfor %}

        {% if categories|length > 6 %}
        <li class="item-nivel1 col-sm-2 sub-left">
            <a href="javascript:void(0)" class="link-nivel1">
                <span>+ Categorias</span>
            </a>
            <ul class="nivel2">
                {% for nivel1 in categories %}
                {% if loop.index > 5 %}
                <li class="item-nivel2">
                    <a href="{{ nivel1.link }}" class="link-nivel2" data-tray-tst="categoria_lvl_1">
                        <span>{{ nivel1.name }}</span>
                    </a>

                    {% if nivel1.children  %}
                    <ul class="nivel3">
                        {% for nivel2 in nivel1.children %}
                        <li class="item-nivel3">
                            <a href="{{ nivel2.link }}" class="link-nivel3" data-tray-tst="categoria_lvl_2">
                                <span>{{ nivel2.name }}</span>
                            </a>

                            {% if nivel2.children  %}
                            <ul class="nivel4">
                                {% for nivel3 in nivel2.children %}
                                <li class="item-nivel4">
                                    <a href="{{ nivel3.link }}" class="link-nivel4" data-tray-tst="categoria_lvl_3">
                                        <span>{{ nivel3.name }}</span>
                                    </a>
                                </li>
                                {% endfor %}
                            </ul>
                            {% endif %}
                        </li>
                        {% endfor %}
                    </ul>
                    {% endif %}
                </li>
                {% endif %}
                {% endfor %}
            </ul>
        </li>
        {% endif %}
    </ul>
</nav>