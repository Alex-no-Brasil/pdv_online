<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">

            <?php if (isset($permissoes['/'])) : ?>
                <li class="mm_welcome">
                    <a href="<?= site_url(); ?>">
                        <i class="fa fa-dashboard"></i> <span><?= lang('dashboard'); ?></span></a>
                </li>
            <?php endif; ?>

            <?php if (isset($permissoes['lojas'])) : ?>
                <li class="treeview mm_lojas">
                    <a href="#">
                        <i class="fa fa-home"></i>
                        <span><?= lang('stores'); ?></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        <?php if (isset($permissoes['/lojas'])) : ?>
                            <li id="lojas_index">
                                <a href="<?= site_url('lojas'); ?>"><i class="fa fa-circle-o"></i>Lista de Lojas</a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/lojas/adicionar'])) : ?>
                            <li id="lojas_add">
                                <a href="<?= site_url('lojas/adicionar'); ?>"><i class="fa fa-circle-o"></i>Adicionar Loja</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>

            <?php endif; ?>

            <?php if (isset($permissoes['depositos'])) : ?>
                <li class="treeview mm_depositos">
                    <a href="#">
                        <i class="fa fa-building"></i>
                        <span>Depósitos</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        <?php if (isset($permissoes['/depositos'])) : ?>
                            <li id="depositos_index">
                                <a href="<?= site_url('depositos'); ?>"><i class="fa fa-circle-o"></i>Lista de Depósitos</a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/depositos/adicionar'])) : ?>
                            <li id="depositos_adicionar">
                                <a href="<?= site_url('depositos/adicionar'); ?>"><i class="fa fa-circle-o"></i>Adicionar Depósito</a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/depositos/entradas'])) : ?>
                            <li id="depositos_entrar">
                                <a href="#" onclick="$('#mdlEntradaEstoque').modal('show');"><i class="fa fa-circle-o"></i>Entrada de Produtos</a>
                            </li>

                            <li id="depositos_entradas">
                                <a href="<?= site_url('depositos/entradas'); ?>"><i class="fa fa-circle-o"></i>Lista de Entrada de Produtos</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (isset($permissoes['products'])) : ?>
                <li class="treeview mm_products">
                    <a href="#">
                        <i class="fa fa-barcode"></i>
                        <span><?= lang('products'); ?></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        <?php if (isset($permissoes['/products'])) : ?>
                            <li id="products_index">
                                <a href="<?= site_url('products'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_products'); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/products/add'])) : ?>
                            <li id="products_add">
                                <a href="<?= site_url('products/add'); ?>"><i class="fa fa-circle-o"></i> <?= lang('add_product'); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/products/transferenciaestoque'])) : ?>
                            <li id="products_transferenciaestoque">
                                <a href="<?= site_url('products/transferenciaestoque'); ?>"><i class="fa fa-circle-o"></i>Transferências de Estoque</a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/products/ajusteestoque'])) : ?>
                            <li id="products_ajusteestoque">
                                <a href="<?= site_url('products/ajusteestoque'); ?>"><i class="fa fa-circle-o"></i>Ajuste de Estoque</a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/products/alteracao_preco'])) : ?>
                            <li id="products_alteracao_preco">
                                <a href="<?= site_url('products/alteracao_preco'); ?>"><i class="fa fa-circle-o"></i>Alteracao de preço</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (isset($permissoes['categories'])) : ?>
                <li class="treeview mm_categories">
                    <a href="#">
                        <i class="fa fa-folder"></i>
                        <span><?= lang('categories'); ?></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        <?php if (isset($permissoes['/categories'])) : ?>
                            <li id="categories_index">
                                <a href="<?= site_url('categories'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_categories'); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/categories/add'])) : ?>
                            <li id="categories_add">
                                <a href="<?= site_url('categories/add'); ?>"><i class="fa fa-circle-o"></i> <?= lang('add_category'); ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (isset($permissoes['sales'])) : ?>
                <li class="treeview mm_sales">
                    <a href="#">
                        <i class="fa fa-shopping-cart"></i>
                        <span><?= lang('sales'); ?></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        <?php if (isset($permissoes['/sales'])) : ?>
                            <li id="sales_index">
                                <a href="<?= site_url('sales'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_sales'); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/sales/opened'])) : ?>
                            <li id="sales_opened">
                                <a href="<?= site_url('sales/opened'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_opened_bills'); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/sales/vendas_geral'])) : ?>
                            <li id="sales_vendas_geral">
                                <a href="<?= site_url('sales/vendas_geral'); ?>"><i class="fa fa-circle-o"></i> <?= lang('Vendas Geral'); ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <!--
            <li class="treeview mm_purchases">
                <a href="#">
                    <i class="fa fa-plus"></i>
                    <span><?= lang('purchases'); ?></span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li id="purchases_index"><a href="<?= site_url('purchases'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_purchases'); ?></a></li>
                    <li id="purchases_add"><a href="<?= site_url('purchases/add'); ?>"><i class="fa fa-circle-o"></i> <?= lang('add_purchase'); ?></a></li>
                    <li class="divider"></li>
                    <li id="purchases_expenses"><a href="<?= site_url('purchases/expenses'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_expenses'); ?></a></li>
                    <li id="purchases_add_expense"><a href="<?= site_url('purchases/add_expense'); ?>"><i class="fa fa-circle-o"></i> <?= lang('add_expense'); ?></a></li>
                </ul>
            </li>
            -->

            <?php if (isset($permissoes['cards'])) : ?>
                <li class="treeview mm_gift_cards mm_cards">
                    <a href="#">
                        <i class="fa fa-credit-card"></i>
                        <span><?= lang('gift_cards'); ?></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <!--
                        <li id="gift_cards_index"><a href="<?= site_url('gift_cards'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_gift_cards'); ?></a></li>
                        <li id="gift_cards_add"><a href="<?= site_url('gift_cards/add'); ?>"><i class="fa fa-circle-o"></i> <?= lang('add_gift_card'); ?></a></li>
                        -->

                        <?php if (isset($permissoes['/cards/tax'])) : ?>
                            <li id="cards_tax">
                                <a href="<?= site_url('cards/tax'); ?>">
                                    <i class="fa fa-circle-o"></i> Taxas
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (isset($permissoes['users']) || isset($permissoes['sellers'])) : ?>
                <li class="treeview mm_auth mm_customers mm_suppliers mm_sellers">
                    <a href="#">
                        <i class="fa fa-users"></i>
                        <span><?= lang('people'); ?></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <?php if (isset($permissoes['/users'])) : ?>
                            <li id="auth_users">
                                <a href="<?= site_url('users'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_users'); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/users/add'])) : ?>
                            <li id="auth_add">
                                <a href="<?= site_url('users/add'); ?>"><i class="fa fa-circle-o"></i> <?= lang('add_user'); ?></a>
                            </li>
                            <li class="divider"></li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/customers'])) : ?>
                            <li id="customers_index">
                                <a href="<?= site_url('customers'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_customers'); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/customers/add'])) : ?>
                            <li id="customers_add">
                                <a href="<?= site_url('customers/add'); ?>"><i class="fa fa-circle-o"></i> <?= lang('add_customer'); ?></a>
                            </li>
                            <li class="divider"></li>
                        <?php endif; ?>
                        <!--
                        <li id="suppliers_index"><a href="<?= site_url('suppliers'); ?>"><i class="fa fa-circle-o"></i> <?= lang('list_suppliers'); ?></a></li>
                        <li id="suppliers_add"><a href="<?= site_url('suppliers/add'); ?>"><i class="fa fa-circle-o"></i> <?= lang('add_supplier'); ?></a></li>                        
                        <li class="divider"></li>
                        -->

                        <?php if (isset($permissoes['/sellers'])) : ?>
                            <li id="sellers_index">
                                <a href="<?= site_url('sellers'); ?>">
                                    <i class="fa fa-circle-o"></i> Lista de Vendedores
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/sellers/add'])) : ?>
                            <li id="sellers_add">
                                <a href="<?= site_url('sellers/add'); ?>">
                                    <i class="fa fa-circle-o"></i> Adicionar Vendedor
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (isset($permissoes['settings'])) : ?>
                <li class="treeview mm_settings">
                    <a href="#">
                        <i class="fa fa-cogs"></i>
                        <span><?= lang('settings'); ?></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li id="settings_index"><a href="<?= site_url('settings'); ?>"><i class="fa fa-circle-o"></i> <?= lang('settings'); ?></a></li>
                        <li id="settings_backups"><a href="<?= site_url('settings/backups'); ?>"><i class="fa fa-circle-o"></i> <?= lang('backups'); ?></a></li>
                        <li id="settings_updates"><a href="<?= site_url('settings/updates'); ?>"><i class="fa fa-circle-o"></i> <?= lang('updates'); ?></a></li>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (isset($permissoes['reports'])) : ?>
                <li class="treeview mm_reports mm_performanceproducts mm_performancelojas mm_performancesellers">
                    <a href="#">
                        <i class="fa fa-bar-chart-o"></i>
                        <span><?= lang('reports'); ?></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        <?php if (isset($permissoes['/reports/daily_sales'])) : ?>
                            <li id="reports_daily_sales">
                                <a href="<?= site_url('reports/daily_sales'); ?>"><i class="fa fa-circle-o"></i> <?= lang('daily_sales'); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/reports/monthly_sales'])) : ?>
                            <li id="reports_monthly_sales">
                                <a href="<?= site_url('reports/monthly_sales'); ?>"><i class="fa fa-circle-o"></i> <?= lang('monthly_sales'); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/reports'])) : ?>
                            <li id="reports_index">
                                <a href="<?= site_url('reports'); ?>"><i class="fa fa-circle-o"></i> <?= lang('sales_report'); ?></a>
                            </li>
                            <li class="divider"></li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/reports/payments'])) : ?>
                            <li id="reports_payments">
                                <a href="<?= site_url('reports/payments'); ?>"><i class="fa fa-circle-o"></i> <?= lang('payments_report'); ?></a>
                            </li>
                            <li class="divider"></li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/reports/registers'])) : ?>
                            <li id="reports_registers">
                                <a href="<?= site_url('reports/registers'); ?>"><i class="fa fa-circle-o"></i> <?= lang('registers_report'); ?></a>
                            </li>
                            <li class="divider"></li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/reports/top_products'])) : ?>
                            <li id="reports_top_products">
                                <a href="<?= site_url('reports/top_products'); ?>"><i class="fa fa-circle-o"></i> <?= lang('top_products'); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/reports/products'])) : ?>
                            <li id="reports_products">
                                <a href="<?= site_url('reports/products'); ?>"><i class="fa fa-circle-o"></i> <?= lang('products_report'); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/reports/performance/products'])) : ?>
                            <li id="performanceproducts_index">
                                <a href="<?= site_url('reports/performance/products'); ?>">
                                    <i class="fa fa-circle-o"></i> Desempenho de Produtos
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/reports/performance/lojas'])) : ?>
                            <li id="performancelojas_index">
                                <a href="<?= site_url('reports/performance/lojas'); ?>">
                                    <i class="fa fa-circle-o"></i> Desempenho de Lojas
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/reports/performance/sellers'])) : ?>
                            <li id="performancesellers_index">
                                <a href="<?= site_url('reports/performance/sellers'); ?>">
                                    <i class="fa fa-circle-o"></i> Desempenho de Vendedores
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (isset($permissoes['notaFiscal'])) : ?>
                <li class="treeview mm_notafiscal">
                    <a href="#">
                        <i class="fa fa-file"></i>
                        <span><?= lang('Nota Fiscal'); ?></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        <?php if (isset($permissoes['/notaFiscal/empresa'])) : ?>
                            <li id="notafiscal_empresa">
                                <a href="<?= site_url('notaFiscal/empresa'); ?>"><i class="fa fa-circle-o"></i> Empresas</a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/notaFiscal/cliente'])) : ?>
                            <li id="notafiscal_cliente">
                                <a href="<?= site_url('notaFiscal/cliente'); ?>"><i class="fa fa-circle-o"></i> Cliente</a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/notaFiscal/transportadora'])) : ?>
                            <li id="notafiscal_transportadora">
                                <a href="<?= site_url('notaFiscal/transportadora'); ?>"><i class="fa fa-circle-o"></i> Transportadora</a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/notaFiscal/estoque'])) : ?>
                            <li id="notafiscal_estoque">
                                <a href="<?= site_url('notaFiscal/estoque'); ?>"><i class="fa fa-circle-o"></i> Estoque</a>
                            </li>
                        <?php endif; ?>
                        <?php if (isset($permissoes['/notaFiscal/nfce'])) : ?>
                            <li id="notafiscal_nfce">
                                <a href="<?= site_url('notaFiscal/nfce'); ?>"><i class="fa fa-circle-o"></i> NFC-e</a>
                            </li>
                        <?php endif; ?>

                    </ul>
                </li>
            <?php endif; ?>

            <?php if (isset($permissoes['stock'])) : ?>
                <li class="treeview mm_stock">
                    <a href="#">
                        <i class="fa fa-archive"></i>
                        <span><?= lang('Estoque'); ?></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        <?php if (isset($permissoes['/stock/estoque'])) : ?>
                            <li id="stock_estoque">
                                <a href="<?= site_url('stock/estoque'); ?>"><i class="fa fa-circle-o"></i> Estoque</a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/stock/foto_estoque'])) : ?>
                            <li id="stock_foto_estoque">
                                <a href="<?= site_url('stock/foto_estoque'); ?>"><i class="fa fa-circle-o"></i> Estoque c/Foto</a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/stock/conferencia'])) : ?>
                            <li id="stock_conferencia">
                                <a href="<?= site_url('stock/conferencia'); ?>"><i class="fa fa-circle-o"></i> Conferência</a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/stock/mapa_loja'])) : ?>
                            <li id="stock_mapa_loja">
                                <a href="<?= site_url('stock/mapa_loja'); ?>"><i class="fa fa-circle-o"></i> Mapa da loja</a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/stock/missao_loja'])) : ?>
                            <li id="stock_missao_loja">
                                <a href="<?= site_url('stock/missao_loja'); ?>"><i class="fa fa-circle-o"></i> Missão Loja</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (isset($permissoes['online'])) : ?>
                <li class="treeview mm_online">
                    <a href="#">
                        <i class="fa fa-hand-o-up"></i>
                        <span><?= lang('Online'); ?></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <?php if (isset($permissoes['/online/pedido'])) : ?>
                            <li id="online_pedido">
                                <a href="<?= site_url('online/pedido'); ?>"><i class="fa fa-circle-o"></i> Pedidos</a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/online/resumo_vendedor'])) : ?>
                            <li class="divider"></li>
                            <li id="online_resumo_vendedor">
                                <a href="<?= site_url('online/resumo_vendedor'); ?>"><i class="fa fa-circle-o"></i> Resumo vendedor</a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/online/resumo_pagamento'])) : ?>
                            <li id="online_resumo_pagamento">
                                <a href="<?= site_url('online/resumo_pagamento'); ?>"><i class="fa fa-circle-o"></i> Resumo Pagamento</a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/online/gerar_romaneio'])) : ?>
                            <li id="online_gerar_romaneio">
                                <a href="<?= site_url('online/gerar_romaneio'); ?>"><i class="fa fa-circle-o"></i> Gerar Romaneio</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (isset($permissoes['oficina'])) : ?>
                <li class="treeview mm_oficina">
                    <a href="#">
                        <i class="fa fa-wrench"></i>
                        <span><?= lang('Oficina'); ?></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <?php if (isset($permissoes['/oficina/piloto_e_corte'])) : ?>
                            <li id="oficina_piloto_e_corte">
                                <a href="<?= site_url('oficina/piloto_e_corte'); ?>"><i class="fa fa-circle-o"></i> <?= lang('Piloto e corte'); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/oficina/andamento'])) : ?>
                            <li id="oficina_andamento">
                                <a href="<?= site_url('oficina/andamento'); ?>"><i class="fa fa-circle-o"></i> <?= lang('Andamento'); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/oficina/corte_pronto'])) : ?>
                            <li id="oficina_corte_pronto">
                                <a href="<?= site_url('oficina/corte_pronto'); ?>"><i class="fa fa-circle-o"></i> <?= lang('Corte pronto'); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/oficina/relatorio_producao'])) : ?>
                            <li id="oficina_relatorio_producao">
                                <a href="<?= site_url('oficina/relatorio_producao'); ?>"><i class="fa fa-circle-o"></i> <?= lang('Relatório produção'); ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (isset($permissoes['producao'])) : ?>
                <li class="treeview mm_producao">
                    <a href="#">
                        <i class="fa fa-wrench"></i>
                        <span><?= lang('Oficina produção'); ?></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <?php if (isset($permissoes['/producao/resumo_custo'])) : ?>
                            <li id="producao_resumo_custo">
                                <a href="<?= site_url('producao/resumo_custo'); ?>">
                                    <i class="fa fa-circle-o"></i> <?= lang('Resumo custo'); ?>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/producao/cadastro_modelista'])) : ?>
                            <li id="producao_cadastro_modelista">
                                <a href="<?= site_url('producao/cadastro_modelista'); ?>">
                                    <i class="fa fa-circle-o"></i> <?= lang('Cadastro modelista'); ?>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/producao/cadastro_cortador'])) : ?>
                            <li id="producao_cadastro_cortador">
                                <a href="<?= site_url('producao/cadastro_cortador'); ?>">
                                    <i class="fa fa-circle-o"></i> <?= lang('Cadastro cortador'); ?>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/producao/cadastro_piloteira'])) : ?>
                            <li id="producao_cadastro_piloteira">
                                <a href="<?= site_url('producao/cadastro_piloteira'); ?>">
                                    <i class="fa fa-circle-o"></i> <?= lang('Cadastro piloteira'); ?>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/producao/cadastro_ampliador'])) : ?>
                            <li id="producao_cadastro_ampliador">
                                <a href="<?= site_url('producao/cadastro_ampliador'); ?>">
                                    <i class="fa fa-circle-o"></i> <?= lang('Cadastro ampliador'); ?>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/producao/cadastro_oficina'])) : ?>
                            <li id="producao_cadastro_oficina">
                                <a href="<?= site_url('producao/cadastro_oficina'); ?>">
                                    <i class="fa fa-circle-o"></i> <?= lang('Cadastro oficina'); ?>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/producao/cadastro_acabamento'])) : ?>
                            <li id="producao_cadastro_acabamento">
                                <a href="<?= site_url('producao/cadastro_acabamento'); ?>">
                                    <i class="fa fa-circle-o"></i> <?= lang('Cadastro acabamento'); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (isset($permissoes['relatorio'])) : ?>
                <li class="treeview mm_relatorio">
                    <a href="#">
                        <i class="fa fa-wrench"></i>
                        <span><?= lang('Oficina relatório'); ?></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <?php if (isset($permissoes['/relatorio/chegada_peca'])) : ?>
                            <li id="relatorio_chegada_peca">
                                <a href="<?= site_url('relatorio/chegada_peca'); ?>"><i class="fa fa-circle-o"></i> <?= lang('Peças'); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/relatorio/chegada_corte'])) : ?>
                            <li id="relatorio_chegada_corte">
                                <a href="<?= site_url('relatorio/chegada_corte'); ?>"><i class="fa fa-circle-o"></i> <?= lang('Cortes'); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/relatorio/chegada_pagamento'])) : ?>
                            <li id="relatorio_chegada_pagamento">
                                <a href="<?= site_url('relatorio/chegada_pagamento'); ?>"><i class="fa fa-circle-o"></i> <?= lang('Pagamento oficina'); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/relatorio/chegada_pagamento_acabamento'])) : ?>
                            <li id="relatorio_chegada_pagamento_acabamento">
                                <a href="<?= site_url('relatorio/chegada_pagamento_acabamento'); ?>"><i class="fa fa-circle-o"></i> <?= lang('Pagamento acabamento'); ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (isset($permissoes['grafico'])) : ?>
                <li class="treeview mm_grafico">
                    <a href="#">
                        <i class="fa fa-wrench"></i>
                        <span><?= lang('Oficina gráfico'); ?></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <?php if (isset($permissoes['/grafico/relatorio_peca'])) : ?>
                            <li id="grafico_relatorio_peca">
                                <a href="<?= site_url('grafico/relatorio_peca'); ?>"><i class="fa fa-circle-o"></i> <?= lang('Peças'); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/grafico/relatorio_corte'])) : ?>
                            <li id="grafico_relatorio_corte">
                                <a href="<?= site_url('grafico/relatorio_corte'); ?>"><i class="fa fa-circle-o"></i> <?= lang('Cortes'); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($permissoes['/grafico/relatorio_receita'])) : ?>
                            <li id="grafico_relatorio_receita">
                                <a href="<?= site_url('grafico/relatorio_receita'); ?>"><i class="fa fa-circle-o"></i> <?= lang('Receita'); ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>
        </ul>
    </section>
</aside>