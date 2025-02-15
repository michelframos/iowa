<ul class="nav pmd-sidebar-nav" id="menu-principal">
    <!-- My Account -->
    <!--
    <li class="dropdown pmd-dropdown">
        <a aria-expanded="false" data-sidebar="true" data-toggle="dropdown" class="btn-user dropdown-toggle media" href="javascript:void(0);">
            <div class="media-left">
                <img width="40" height="40" alt="avatar" src="http://propeller.in/assets/images/avatar-icon-40x40.png">
            </div>
            <div class="media-body media-middle">D,Material Admin</div>
            <div class="media-right media-middle"><i class="material-icons pmd-sm">more_vert</i></div>
        </a>
        <ul class="dropdown-menu">
            <li> <a class="pmd-ripple-effect" href="javascript:void(0);" tabindex="-1"><i class="material-icons media-left media-middle">person</i> <span class="media-body">View Profile</span></a></li>
            <li> <a class="pmd-ripple-effect" href="javascript:void(0);" tabindex="-1"><i class="material-icons media-left media-middle">settings</i> <span class="media-body">Settings</span></a></li>
            <li> <a class="pmd-ripple-effect" href="javascript:void(0);" tabindex="-1"><i class="material-icons media-left media-middle">history</i> <span class="media-body">Logout</span></a></li>
        </ul>
    </li>
    -->
    <li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-inicio" tabindex="-1"><i class="material-icons media-left media-middle">home</i> <span class="media-body">INÍCIO</span></a></li>
    <li class="dropdown pmd-dropdown">
        <a href="javascript:void(0);" aria-expanded="false" data-sidebar="true" data-toggle="dropdown" class="pmd-ripple-effect btn-user dropdown-toggle media"><i class="material-icons media-left media-middle">assignment_late</i> <span class="media-body">GERAL </span></a>
        <ul class="dropdown-menu">
            <?php if(MostrarMenu(idUsuario(), 'Categorias de Usuários') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-configuracao-emails" tabindex="-1"><i class="material-icons media-left media-middle">drafts</i> <span class="media-body">Configuração de <br> Envio de Emails</span></a></li> <?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Categorias de Usuários') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-perfis" tabindex="-1"><i class="material-icons media-left media-middle">portrait</i> <span class="media-body">Categorias de Usuários</span></a></li> <?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Histórico de Ações') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-historico-acoes" tabindex="-1"><i class="material-icons media-left media-middle">history</i> <span class="media-body">Histórico de Ações dos Usuários</span></a></li> <?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Usuários') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-usuarios" tabindex="-1"><i class="material-icons media-left media-middle">account_circle</i> <span class="media-body">Usuarios</span></a></li> <?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Idiomas') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-idiomas" tabindex="-1"><i class="material-icons media-left media-middle">translate</i> <span class="media-body">Idiomas</span></a></li> <?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Nomes de Provas') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-nomes-provas" tabindex="-1"><i class="material-icons media-left media-middle">content_paste</i> <span class="media-body">Nomes de Provas</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Sistemade Notas') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-sistema-notas" tabindex="-1"><i class="material-icons media-left media-middle">format_shapes</i> <span class="media-body">Sistema de Notas</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Unidades') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-unidades" tabindex="-1"><i class="material-icons media-left media-middle">place</i> <span class="media-body">Unidades</span></a></li><?php endif; ?>
            <!--<li> <a class="pmd-ripple-effect" href="javascript:void(0);" tabindex="-1"><i class="material-icons media-left media-middle">beenhere</i> <span class="media-body">Função</span></a></li>-->
            <?php if(MostrarMenu(idUsuario(), 'Valores Hora/Aula') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-valores" tabindex="-1"><i class="material-icons media-left media-middle">monetization_on</i> <span class="media-body">Valores Hora/Aula</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Nomes de Produtos e Horas Semanais') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-nomes-produtos" tabindex="-1"><i class="material-icons media-left media-middle">business_center</i> <span class="media-body">Nomes de Produtos <br/>e Horas Semanais</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Programação e Conteúdo de Aulas') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-programacao" tabindex="-1"><i class="material-icons media-left media-middle">assignment</i> <span class="media-body">Programação e <br/>Conteúdo de Aulas</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Origem do Aluno') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-origem-aluno" tabindex="-1"><i class="material-icons media-left media-middle">add_location</i> <span class="media-body">Origem do Aluno</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Motivos de Desistência') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-motivos-desistencia" tabindex="-1"><i class="material-icons media-left media-middle">thumb_down</i> <span class="media-body">Motivos de Desistência</span></a></li><?php endif; ?>
            <li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-editor-documentos" tabindex="-1"><i class="material-icons media-left media-middle">edit</i> <span class="media-body">Editor de Documentos</span></a></li>
        </ul>
    </li>

    <li class="dropdown pmd-dropdown">
        <a href="javascript:void(0);" aria-expanded="false" data-sidebar="true" data-toggle="dropdown" class="pmd-ripple-effect btn-user dropdown-toggle media"><i class="material-icons media-left media-middle">folder_open</i> <span class="media-body">CADASTROS </span></a>
        <ul class="dropdown-menu">
            <?php if(MostrarMenu(idUsuario(), 'Empresas') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-empresas" tabindex="-1"><i class="material-icons media-left media-middle">store</i> <span class="media-body">Empresas</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Colegas IOWA') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-colegas" tabindex="-1"><i class="material-icons media-left media-middle">assignment_ind</i> <span class="media-body">Colegas IOWA</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Turmas') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-turmas" tabindex="-1"><i class="material-icons media-left media-middle">group_add</i> <span class="media-body">Turmas</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Alunos') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-alunos" tabindex="-1"><i class="material-icons media-left media-middle">school</i> <span class="media-body">Alunos</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Promoções') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-promocoes" tabindex="-1"><i class="material-icons media-left media-middle">grade</i> <span class="media-body">Promoções</span></a></li><?php endif; ?>
        </ul>
    </li>

    <li class="dropdown pmd-dropdown"><a href="javascript:void(0);" aria-expanded="false" data-sidebar="true" data-toggle="dropdown" class="pmd-ripple-effect btn-user dropdown-toggle media"><i class="material-icons media-left media-middle">monetization_on</i> <span class="media-body">FINANCEIRO </span></a>
        <ul class="dropdown-menu">
            <?php if(MostrarMenu(idUsuario(), 'Fornecedores') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-fornecedores" tabindex="-1"><i class="material-icons media-left media-middle">local_shipping</i> <span class="media-body">Fornecedores</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Categorias de Lançamentos') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-categorias-lancamentos" tabindex="-1"><i class="material-icons media-left media-middle">label</i> <span class="media-body">Categorias de <br> Lançamentos</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Formas de Recebimento/Pagamento') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-formas-recebimento" tabindex="-1"><i class="material-icons media-left media-middle">credit_card</i> <span class="media-body">Formas de <br> Recebimento / <br> Pagamento</span></a></li><?php endif; ?>

            <?php if(MostrarMenu(idUsuario(), 'Abrir Caixa') == true || MostrarMenu(idUsuario(), 'Fechar Caixa') == true || MostrarMenu(idUsuario(), 'Fazer Transferência') == true || MostrarMenu(idUsuario(), 'Ver Todos os Caixas') == true): ?>
            <li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-caixas" tabindex="-1"><i class="material-icons media-left media-middle">attach_money</i> <span class="media-body">Caixas</span></a></li>
            <?php endif; ?>

            <?php if(MostrarMenu(idUsuario(), 'Geração de Cobrança') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-cobranca" tabindex="-1"><i class="material-icons media-left media-middle">confirmation_number</i> <span class="media-body">Geração de Cobrança</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Gestão de Boletos') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-gestao-boletos" tabindex="-1"><i class="material-icons media-left media-middle">confirmation_number</i> <span class="media-body">Gestão de Boletos</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Natureza de Contas a Pagar') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-natureza" tabindex="-1"><i class="material-icons media-left media-middle">label</i> <span class="media-body">Natureza de<br/> Contas a Pagar</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Valor Original da Parcela') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-valor-original" tabindex="-1"><i class="material-icons media-left media-middle">monetization_on</i> <span class="media-body">Valor Original Parcela</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Contas a Receber') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-contas-receber" tabindex="-1"><i class="material-icons media-left media-middle">receipt</i> <span class="media-body">Contas a Receber</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Contas a Pagar') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-contas-pagar" tabindex="-1"><i class="material-icons media-left media-middle">receipt</i> <span class="media-body">Contas a Pagar</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Contas a Pagar') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-renovacao" tabindex="-1"><i class="material-icons media-left media-middle">restore_page</i> <span class="media-body">Renovação de Contrato</span></a></li><?php endif; ?>
        </ul>
    </li>

    <li class="dropdown pmd-dropdown"><a href="javascript:void(0);" aria-expanded="false" data-sidebar="true" data-toggle="dropdown" class="pmd-ripple-effect btn-user dropdown-toggle media"><i class="material-icons media-left media-middle">live_help</i> <span class="media-body">HELP & COACH </span></a>
        <ul class="dropdown-menu">
            <?php if(MostrarMenu(idUsuario(), 'Help') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-help" tabindex="-1"><i class="material-icons media-left media-middle">help</i> <span class="media-body">HELP</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Coachs') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-coachs" tabindex="-1"><i class="material-icons media-left media-middle">person</i> <span class="media-body">COACHs</span></a></li><?php endif; ?>
        </ul>
    </li>

    <li class="dropdown pmd-dropdown"><a href="javascript:void(0);" aria-expanded="false" data-sidebar="true" data-toggle="dropdown" class="pmd-ripple-effect btn-user dropdown-toggle media"><i class="material-icons media-left media-middle">description</i> <span class="media-body">RELATÓRIOS </span></a>
        <ul class="dropdown-menu">
            <?php if(MostrarMenu(idUsuario(), 'Relatório - Colegas IOWA') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-relatorio-colegas" tabindex="-1"><i class="material-icons media-left media-middle">description</i> <span class="media-body">Colegas IOWA</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Relatório - Folha de Pagamento') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-relatorio-folha-pagamento" tabindex="-1"><i class="material-icons media-left media-middle">description</i> <span class="media-body">FOLHA DE PAGAMENTO</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Relatório - Folha de Pagamento Por Unidade') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-relatorio-folha-unidade" tabindex="-1"><i class="material-icons media-left media-middle">description</i> <span class="media-body">FOLHA DE PAGAMENTO <br> POR UNIDADE</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Relatório - Turmas') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-relatorio-turmas" tabindex="-1"><i class="material-icons media-left media-middle">description</i> <span class="media-body">TURMAS</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Relatório - Alunos / Turmas') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-relatorio-alunos-turmas" tabindex="-1"><i class="material-icons media-left media-middle">description</i> <span class="media-body">ALUNOS / TURMAS</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Relatório - Alunos / Empresas') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-relatorio-alunos-empresas" tabindex="-1"><i class="material-icons media-left media-middle">description</i> <span class="media-body">ALUNOS / EMPRESAS</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Relatório - Ocorrências ou Aulas') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-relatorio-ocorrencias" tabindex="-1"><i class="material-icons media-left media-middle">description</i> <span class="media-body">OCORRÊNCIAS OU AULAS</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Relatório - Frequencia') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-relatorio-frequencia" tabindex="-1"><i class="material-icons media-left media-middle">description</i> <span class="media-body">FREQUENCIA</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Relatório - F7') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-relatorio-f7" tabindex="-1"><i class="material-icons media-left media-middle">description</i> <span class="media-body">RELATÓRIO F7</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Relatório - Consolidado de Faltas') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-consolidado-faltas" tabindex="-1"><i class="material-icons media-left media-middle">description</i> <span class="media-body">CONSOLIDADO DE FALTAS</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Relatório - Contas a Receber') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-relatorio-contas-receber" tabindex="-1"><i class="material-icons media-left media-middle">description</i> <span class="media-body">CONTAS A RECEBER</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Relatório - Contas a Pagar') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-relatorio-contas" tabindex="-1"><i class="material-icons media-left media-middle">description</i> <span class="media-body">CONTAS A PAGAR</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Relatório - Faturamento') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-relatorio-faturamento" tabindex="-1"><i class="material-icons media-left media-middle">description</i> <span class="media-body">FATURAMENTO</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Relatório - Matrículas Efetuadas') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-relatorio-matriculas" tabindex="-1"><i class="material-icons media-left media-middle">description</i> <span class="media-body">MATRÍCULAS EFETUADAS</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Relatório - Matrículas Por Unidade') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-relatorio-matriculas-unidade" tabindex="-1"><i class="material-icons media-left media-middle">description</i> <span class="media-body">MATRÍCULAS <br> POR UNIDADE</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Relatório - Inativação de Aluno') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-relatorio-inativacao-alunos" tabindex="-1"><i class="material-icons media-left media-middle">description</i> <span class="media-body">INATIVAÇÃO DE ALUNO</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Relatório - Helps') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-relatorio-helps" tabindex="-1"><i class="material-icons media-left media-middle">description</i> <span class="media-body">HELPS</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Relatório - Aniversariantes') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-relatorio-aniversariantes" tabindex="-1"><i class="material-icons media-left media-middle">description</i> <span class="media-body">ANIVERSARIANTES</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Relatório - Aluno - Material') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-aluno-material" tabindex="-1"><i class="material-icons media-left media-middle">description</i> <span class="media-body">ALUNO - MATERIAL</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Relatório - Alunos Por Unidade') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-alunos-unidade" tabindex="-1"><i class="material-icons media-left media-middle">description</i> <span class="media-body">ALUNO POR UNIDADE</span></a></li><?php endif; ?>
            <?php if(MostrarMenu(idUsuario(), 'Relatório - Email Marketing') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-email-marketing" tabindex="-1"><i class="material-icons media-left media-middle">description</i> <span class="media-body">E-MAIL MARKETING</span></a></li><?php endif; ?>
            <?php //if(MostrarMenu(idUsuario(), 'Relatório - Logins') == true): ?><li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-logins" tabindex="-1"><i class="material-icons media-left media-middle">description</i> <span class="media-body">LOGINS DOS ALUNOS</span></a></li><?php //endif; ?>
        </ul>
    </li>

    <li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-central-conhecimento" tabindex="-1"><i class="material-icons media-left media-middle">textsms</i> <span class="media-body">CENTRAL DE CONHECIMENTO</span></a></li>

    <li> <a class="pmd-ripple-effect" href="javascript:void(0);" id="menu-whatsapp" tabindex="-1"><i class="material-icons media-left media-middle">textsms</i> <span class="media-body">WHATSAPP</span></a></li>

</ul>
