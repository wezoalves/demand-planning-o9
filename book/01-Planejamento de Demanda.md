# 📊 Capítulo 1 — Planejamento de Demanda

## 1. Fundamentos do Planejamento de Demanda

O planejamento de demanda existe para equilibrar dois fatores críticos da cadeia de suprimentos:

* **Lead times da operação (buy–make–sell):** o tempo necessário para comprar insumos, produzir e entregar produtos.
* **Nível de serviço ao cliente:** a capacidade de atender pedidos dentro do prazo que o cliente considera aceitável.

Se a empresa esperar o pedido chegar para começar a comprar e produzir, dificilmente conseguirá atender no tempo esperado. Por isso, é preciso **antecipar a demanda**, garantindo que materiais estejam comprados, fábricas preparadas e estoques dimensionados.

👉 Em resumo, o planejamento de demanda serve para alinhar **estoques, produção e distribuição** às expectativas do mercado, evitando rupturas e excesso de produtos parados.

---

## 2. Estrutura e Hierarquia do Plano de Demanda

Um bom plano de demanda precisa estar organizado em **dimensões, hierarquias e níveis de agregação**:

* **Dimensões:** formas de categorizar os dados (ex.: produto, cliente, tempo, canal).
* **Hierarquias:** como os dados são estruturados dentro da dimensão (ex.: item → grupo → categoria).
* **Níveis:** diferentes “alturas” da análise, do detalhe (SKU, loja, dia) até o consolidado (categoria, país, trimestre).

### Exemplos práticos:

* **Produto:** SKU → Linha → Categoria → Todos os produtos.
* **Clientes:** Loja → Cidade → Região → País → Todos os clientes.
* **Tempo:** Dia → Semana → Mês → Ano.

Cada processo usa o nível adequado:

* **Coleta de dados:** detalhado (SKU, cliente, dia).
* **KPIs estratégicos:** agregado (categoria, trimestre).
* **Forecast estatístico:** níveis médios, que capturam sazonalidade e tendência sem perder granularidade.
* **Publicação:** múltiplos níveis, de acordo com a área usuária (ex.: fábrica precisa de SKU/semana, finanças precisa de categoria/mês).

---

## 3. Métodos Estatísticos, Agregação e Desagregação

Um desafio do planejamento é equilibrar dados detalhados (ruidosos e instáveis) com visões agregadas (mais estáveis, mas menos específicas).
Por isso, sistemas modernos permitem **gerar forecasts em múltiplos níveis** e depois **desagregar** para o detalhe ou **agregar** para consolidação.

Exemplo:

* Forecast gerado em **item + grupo de clientes + mês**.
* O sistema **desagrega** para SKU + loja + semana.
* Depois, os resultados podem ser **agregados** novamente para categoria + região + trimestre.

Isso evita vieses e garante que o plano sirva para decisões de curto, médio e longo prazo.

---

## 4. Riscos e Oportunidades

O ambiente de negócios muda constantemente, e apenas olhar para o histórico não basta.
É necessário incluir **riscos** (ameaças que podem reduzir a demanda) e **oportunidades** (eventos que podem aumentá-la).

Com a plataforma o9, gestores podem:

* Criar riscos/oportunidades dentro do sistema.
* Simular seu impacto em volume, receita e margens.
* Incluir ou não no plano final após consenso.
* Construir um repositório com todos os riscos mapeados (com responsáveis, probabilidades, datas e comentários).

👉 Esse processo aumenta a **agilidade**, permitindo decisões rápidas em reuniões de S\&OP/IBP.

---

## 5. Precisão de Forecast e o Cockpit de Análise

Tradicionalmente, avaliava-se a precisão do forecast só **depois** que o resultado ocorria. Isso é reativo.
O cockpit da o9 permite uma abordagem proativa, avaliando a **qualidade do forecast no momento em que ele é criado**.

Exemplos de violações detectadas automaticamente:

* **Tendência incorreta:** histórico cresce, mas forecast cai.
* **Sazonalidade ignorada:** padrão de alta/baixa não refletido no plano.
* **Níveis desalinhados:** previsão muito acima ou abaixo da realidade.
* **Picos e vales exagerados:** variações fora do esperado.

O cockpit sugere ajustes, compara algoritmos e ajuda o planejador a focar onde realmente há problema.
O resultado é **mais precisão, menos esforço manual e maior transparência**.

---

## 6. Dificuldades Práticas no Varejo

Na prática, muitos planejadores enfrentam um cenário caótico:

* Recebem informações de várias equipes em formatos diferentes.
* Precisam consolidar planilhas manuais e relatórios dispersos.
* O sistema estatístico gera previsões que não consideram promoções ou lançamentos.
* Equipes de supply ignoram o forecast porque não enxergam os pressupostos.

Esse desalinhamento gera **múltiplas versões da verdade**, dificultando a responsabilização e comprometendo a tomada de decisão.

---

## 7. Abordagens Modernas: Ágil, Digital Twins e IA

O futuro do planejamento de demanda vai além de planilhas e forecasts básicos.
Abordagens modernas incluem:

* **Planejamento Ágil:** ciclos semanais em vez de mensais, permitindo reagir mais rápido a mudanças no mercado.
* **Digital Twins:** réplicas digitais do negócio, que permitem testar cenários “e se...” (What-If) antes de decidir.
* **Inteligência Artificial:** uso de machine learning e indicadores externos (como buscas no Google, mobilidade, clima) para enriquecer o forecast.

Exemplo prático:
Durante a pandemia, dados de mobilidade ajudaram a explicar o aumento da demanda por jogos de tabuleiro.
Outro caso: buscas por vitaminas anteciparam a explosão de consumo de suplementos.

👉 O digital brain da o9 conecta dados internos e externos, identifica correlações e gera insights preditivos que aumentam a **precisão e a confiança** do forecast.

---

✅ **Resumo do Capítulo 1**
O planejamento de demanda é a base para equilibrar supply chain e atendimento ao cliente.
Combinando **estruturas hierárquicas**, **métodos estatísticos**, **simulações de risco**, **cockpit de análise** e **IA**, é possível transformar um processo reativo em uma prática estratégica, colaborativa e de alto impacto para o negócio.
