<?php

namespace App\Controller\Admin;

use Cake\Http\Exception\NotFoundException;

/**
 * @property \App\Model\Table\InvoicesTable $Invoices
 */
class InvoicesController extends AppAdminController
{
    public function index()
    {
        $query = $this->Invoices->find()->contain(['Users']);
        $invoices = $this->paginate($query);

        $this->set('invoices', $invoices);
    }

    public function view($id = null)
    {
        if (!$id) {
            throw new NotFoundException(__('Invalid Invoice'));
        }

        $invoice = $this->Invoices->findById($id)->contain(['Users'])->first();
        if (!$invoice) {
            throw new NotFoundException(__('Invalid Invoice'));
        }
        $this->set('invoice', $invoice);
    }

    public function markPaid($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);

        $invoice = $this->Invoices->findById($id)->where(['status <>' => 1])->first();

        $invoice->status = 1;
        $invoice->paid_date = date("Y-m-d H:i:s");
        $this->Invoices->save($invoice);

        $this->Invoices->successPayment($invoice);

        $this->Flash->success(__('The invoice with id: {0} has been marked as paid.', $invoice->id));

        return $this->redirect(['action' => 'index']);
    }

    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);

        $invoice = $this->Invoices->findById($id)->first();

        if ($this->Invoices->delete($invoice)) {
            $this->Flash->success(__('The invoice with id: {0} has been deleted.', $invoice->id));

            return $this->redirect(['action' => 'index']);
        }
    }
}
