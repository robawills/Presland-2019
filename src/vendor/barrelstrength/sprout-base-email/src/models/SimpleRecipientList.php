<?php

namespace barrelstrength\sproutbaseemail\models;

use craft\base\Model;

/**
 * Represents a list of Simple Recipients
 *
 * @property array $recipientEmails
 */
class SimpleRecipientList extends Model
{
    /**
     * An array of all valid recipients
     *
     * @var SimpleRecipient[]
     */
    protected $recipients = [];

    /**
     * An array of invalid recipients
     *
     * @var array
     */
    protected $invalidRecipients = [];

    /**
     * @param SimpleRecipient $recipient
     */
    public function addRecipient(SimpleRecipient $recipient)
    {
        $this->recipients[] = $recipient;
    }

    /**
     * @return array
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }

    /**
     * @return array
     */
    public function getRecipientEmails(): array
    {
        $recipients = $this->recipients;
        $emails = [];
        if ($recipients) {
            foreach ($recipients as $recipient) {
                $emails[] = $recipient->email;
            }
        }

        return $emails;
    }

    /**
     * @param SimpleRecipient $recipient
     */
    public function addInvalidRecipient(SimpleRecipient $recipient)
    {
        $this->invalidRecipients[] = $recipient;
    }

    /**
     * @return array
     */
    public function getInvalidRecipients(): array
    {
        return $this->invalidRecipients;
    }
}
