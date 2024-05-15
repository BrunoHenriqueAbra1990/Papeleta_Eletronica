


	<div class="modal fade modalRecusarRegistro" id="modalRecusarRegistro" tabindex="-1" aria-labelledby="visualizaValorModalLabel" aria-hidden="true">
		<div class="modal-dialog " role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title text-center " id="visualizaNotaModalLabel" >
						<label>Recusar Pagamento: </label>
						<input type="hidden" id="id_recusar_modal" name="" class="col-md-12 " />
					</h5>
					&nbsp;&nbsp;&nbsp;
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body" style="background-color:#eaedee">
					<div class="form-group" id="">
						<div class="form-row contorno_novo">
							<strong>Observação do Registro</strong>
							<textarea id="observacao_papeleta" name="observacao_papeleta" row="3" class="form-control col-md-12"></textarea>
						</div><br>
						
						<button type="button" class="btn btn-danger far fa-times-circle" data-dismiss="modal">&nbsp; Cancelar</button>
						<button type="submit" class="btn btn-success far fa-save" onclick='salvarRecusa()'  data-dismiss="modal" id="btnUpdateNomes" name="btnSalvar_OC" style="float: right;">&nbsp; Salvar</button>
					</div>
				</div>
			</div>
		</div>
	</div>

<!-- MODAL VISUALIZAR DETALHES -->
		<div class="modal fade modalVisualizaRegistro" id="modalVisualizaRegistro" tabindex="-1" aria-labelledby="visualizaModalLabel" aria-hidden="true">
			<div class="modal-dialog  modal-xl" role="document">
				<div class="modal-content">
					<div class="modal-header ">
						<h5 class="modal-title text-center col-md-9 " id="visualizaModalLabel" >
							<label class="col-md-12 ">Visualzar Registro Detalhado: </label>
						</h5>
						<button type="button" class="close " data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					</div>
					<div class="modal-body" style="background-color:#eaedee">
						<span id="msgAlertaVisualizar"></span>
						<b style="color:red">						</b>
						<div class=" form-row col-md-12" id="conteudo_modal_visualizar">
							
						</div>
					</div>
				</div>
			</div>
		</div>