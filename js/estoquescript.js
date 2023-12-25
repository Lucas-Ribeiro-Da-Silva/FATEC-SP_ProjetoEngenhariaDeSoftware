const modal = document.querySelector('.modal-container')
const tbody = document.querySelector('tbody')
const sProduto = document.querySelector('#m-produto')
const sCategoria = document.querySelector('#m-categoria')
const sQuantidade = document.querySelector('#m-quantidade') 
const sPreco = document.querySelector('#m-preco')
const btnSalvar = document.querySelector('#btnSalvar')

let itens
let id

function openModal(edit = false, index = 0) {
  modal.classList.add('active')

  modal.onclick = e => {
    if (e.target.className.indexOf('modal-container') !== -1) {
      modal.classList.remove('active')
    }
  }

  if (edit) {
    sProduto.value = itens[index].produto
    sCategoria.value = itens[index].categoria
    sQuantidade.value = itens[index].quantidade
    sPreco.value = itens[index].preco
    id = index
  } else {
    sProduto.value = ''
    sCategoria.value = ''
    sQuantidade.value = ''
    sPreco.value = ''
  }
  
}

function editItem(index) {

  openModal(true, index)
}

function deleteItem(index) {
  itens.splice(index, 1)
  setItensBD()
  loadItens()
}

function insertItem(item, index) {
  let tr = document.createElement('tr')

  tr.innerHTML = `
    <td>${item.produto}</td>
    <td>${item.categoria}</td>
    <td>${item.quantidade}</td>
    <td>R$ ${item.preco}</td>
    <td class="acao">
      <button onclick="editItem(${index})"><i class='bx bx-edit' ></i></button>
    </td>
    <td class="acao">
      <button onclick="deleteItem(${index})"><i class='bx bx-trash'></i></button>
    </td>
  `
  tbody.appendChild(tr)
}

btnSalvar.onclick = e => {
  
  if (sProduto.value == '' || sCategoria.value == ''|| sQuantidade.value == '' || sPreco.value == '' ) {
    return
  }

  e.preventDefault();

  if (id !== undefined) {
    itens[id].produto = sProduto.value
    itens[id].categoria = sCategoria.value
    itens[id].quantidade = sQuantidade.value
    itens[id].preco = sPreco.value
  } else {
    itens.push({'produto': sProduto.value, 'quantidade': sQuantidade.value,'categoria': sCategoria.value, 'preço': sPreco.value})
  }

  setItensBD()

  modal.classList.remove('active')
  loadItens()
  id = undefined
}

function loadItens() {
  itens = getItensBD()
  tbody.innerHTML = ''
  itens.forEach((item, index) => {
    insertItem(item, index)
  })

}

const getItensBD = () => JSON.parse(localStorage.getItem('dbfunc')) ?? []
const setItensBD = () => localStorage.setItem('dbfunc', JSON.stringify(itens))

loadItens()
